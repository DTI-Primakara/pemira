<?php

use App\Filament\Resources\Voters\Pages\ListVoters;
use App\Filament\Resources\VoterSyncRuns\Pages\ListVoterSyncRuns;
use App\Jobs\ImportSiskaStudentPage;
use App\Models\Period;
use App\Models\User;
use App\Models\UserVote;
use App\Models\VoterSyncRun;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

beforeEach(function () {
    config()->set('services.siska', [
        'host' => 'https://siska.test',
        'api_key' => 'siska-key',
        'status_aktif_id' => 1,
        'per_page' => 100,
        'timeout' => 30,
    ]);
});

/**
 * One row in the shape SISKA's public student API returns it.
 */
function siskaStudent(array $overrides = []): array
{
    return array_merge([
        'id_mahasiswa' => fake()->unique()->numberBetween(1, 100000),
        'nim' => '2301010001',
        'nama_mahasiswa' => 'Mahasiswa Satu',
        'email_kampus' => null,
        'email_pribadi' => 'satu@primakara.test',
        'prodi' => ['id_ref_prodi' => 1, 'kode_prodi' => 'S1-SI', 'nama_prodi' => 'Sistem Informasi'],
        'status_mahasiswa' => ['id_ref_status_mahasiswa' => 1, 'kode' => 'A', 'nama_status_mahasiswa' => 'Aktif'],
    ], $overrides);
}

/**
 * Fake the API with one page per entry in $pages.
 *
 * Keyed on the requested page rather than a response sequence, so a test may
 * pull twice without the stub running dry.
 */
function fakeSiska(array $pages): void
{
    $lastPage = max(1, count($pages));
    $total = collect($pages)->flatten(1)->count();

    Http::fake([
        'siska.test/api/v1/mahasiswa*' => function ($request) use ($pages, $lastPage, $total) {
            parse_str((string) parse_url($request->url(), PHP_URL_QUERY), $query);

            $page = max(1, (int) ($query['page'] ?? 1));

            return Http::response([
                'success' => true,
                'data' => $pages[$page - 1] ?? [],
                'meta' => [
                    'current_page' => $page,
                    'per_page' => 100,
                    'total' => $total,
                    'last_page' => $lastPage,
                ],
            ]);
        },
    ]);
}

/** Start a run and work it to completion, pages and all. */
function runSiskaSync(): VoterSyncRun
{
    $run = VoterSyncRun::create([
        'period_id' => Period::activeId(),
        'status' => VoterSyncRun::STATUS_RUNNING,
        'started_at' => now()->subSecond(),
    ]);

    // The pages chain by dispatching one another; the test loop stands in for
    // the queue worker.
    $page = 1;

    do {
        (new ImportSiskaStudentPage($run->id, $page))->handle(
            app(App\Services\SiskaService::class),
            app(App\Services\VoterRollSynchroniser::class),
        );

        $run->refresh();
        $page++;
    } while ($run->status === VoterSyncRun::STATUS_RUNNING && $page <= ($run->last_page ?? 1));

    return $run->refresh();
}

it('enrols every active student as a voter of the running period', function () {
    fakeSiska([[
        siskaStudent(),
        siskaStudent(['nim' => '2301020002', 'nama_mahasiswa' => 'Mahasiswa Dua', 'email_pribadi' => 'dua@primakara.test',
            'prodi' => ['kode_prodi' => 'S1-TI']]),
    ]]);

    $run = runSiskaSync();

    expect($run->status)->toBe(VoterSyncRun::STATUS_COMPLETED)
        ->and($run->created_count)->toBe(2)
        ->and($run->enrolled_count)->toBe(2)
        ->and(Period::active()->voters()->count())->toBe(2);

    $voter = User::query()->where('nim', '2301010001')->sole();

    expect($voter->email)->toBe('satu@primakara.test')
        ->and((int) $voter->roles)->toBe(User::ROLE_PARTICIPANT)
        ->and($voter->belongsToActivePeriod())->toBeTrue();
});

it('walks every page of the list', function () {
    fakeSiska([
        [siskaStudent(['nim' => '2301010001', 'email_pribadi' => 'a@primakara.test'])],
        [siskaStudent(['nim' => '2301010002', 'email_pribadi' => 'b@primakara.test'])],
        [siskaStudent(['nim' => '2301010003', 'email_pribadi' => 'c@primakara.test'])],
    ]);

    $run = runSiskaSync();

    expect($run->status)->toBe(VoterSyncRun::STATUS_COMPLETED)
        ->and($run->current_page)->toBe(3)
        ->and($run->seen_count)->toBe(3)
        ->and(Period::active()->voters()->count())->toBe(3);
});

it('claims the account an SSO login already created instead of making a second one', function () {
    // Exactly the state after a student signs in before being enrolled: the
    // row carries the SSO address, which need not match SISKA's.
    $selfServed = User::factory()->notEnrolled()->create([
        'name' => 'Nama Lama',
        'nim' => '2301010001',
        'email' => 'alamat.sso@gmail.test',
    ]);

    fakeSiska([[siskaStudent(['nama_mahasiswa' => 'Nama Benar', 'email_pribadi' => 'alamat.siska@primakara.test'])]]);

    $run = runSiskaSync();

    expect(User::query()->count())->toBe(1)
        ->and($run->created_count)->toBe(0)
        ->and($run->updated_count)->toBe(1)
        ->and($selfServed->fresh()->name)->toBe('Nama Benar')
        ->and($selfServed->fresh()->email)->toBe('alamat.siska@primakara.test')
        ->and($selfServed->fresh()->belongsToActivePeriod())->toBeTrue();
});

it('matches by e-mail when the local account has no student number yet', function () {
    $existing = User::factory()->notEnrolled()->create(['nim' => null, 'email' => 'satu@primakara.test']);

    fakeSiska([[siskaStudent()]]);

    runSiskaSync();

    expect(User::query()->count())->toBe(1)
        ->and($existing->fresh()->nim)->toBe('2301010001');
});

it('uses the campus e-mail only when there is no personal one', function () {
    fakeSiska([[siskaStudent(['email_pribadi' => null, 'email_kampus' => 'kampus@primakara.test'])]]);

    runSiskaSync();

    expect(User::query()->where('email', 'kampus@primakara.test')->exists())->toBeTrue();
});

it('treats a blank personal e-mail as no e-mail at all, exactly as the SSO does', function () {
    // SISKA's own rule is `email_pribadi ?? email_kampus`, so an empty string
    // does not fall through. Diverging here would hand the student a different
    // address than their SSO account carries.
    fakeSiska([[siskaStudent(['email_pribadi' => '', 'email_kampus' => 'kampus@primakara.test'])]]);

    $run = runSiskaSync();

    expect(User::query()->count())->toBe(0)
        ->and($run->skipped_count)->toBe(1)
        ->and($run->issues[0]['reason'])->toContain('Belum punya email');
});

it('skips a student whose status is not active even if the filter let them through', function () {
    fakeSiska([[siskaStudent(['status_mahasiswa' => ['kode' => 'L', 'nama_status_mahasiswa' => 'Lulus']])]]);

    $run = runSiskaSync();

    expect(User::query()->count())->toBe(0)
        ->and($run->skipped_count)->toBe(1)
        ->and($run->issues[0]['reason'])->toContain('bukan Aktif');
});

it('takes a student who is no longer active off the roll', function () {
    $graduated = User::factory()->create(['nim' => '2301019999', 'email' => 'lulus@primakara.test']);

    expect($graduated->belongsToActivePeriod())->toBeTrue();

    // Enough active students that the half-empty guard does not trip.
    fakeSiska([[
        siskaStudent(['nim' => '2301010001', 'email_pribadi' => 'a@primakara.test']),
        siskaStudent(['nim' => '2301010002', 'email_pribadi' => 'b@primakara.test']),
    ]]);

    $run = runSiskaSync();

    expect($run->removed_count)->toBe(1)
        ->and($graduated->fresh()->belongsToActivePeriod())->toBeFalse()
        // The account itself is never deleted, only its place on this roll.
        ->and(User::query()->whereKey($graduated->id)->exists())->toBeTrue();
});

it('never takes someone off the roll once they have voted', function () {
    $voted = User::factory()->create(['nim' => '2301019999', 'email' => 'sudah@primakara.test']);
    $voted->ballot()->update(['status' => UserVote::STATUS_VOTED]);

    fakeSiska([[
        siskaStudent(['nim' => '2301010001', 'email_pribadi' => 'a@primakara.test']),
        siskaStudent(['nim' => '2301010002', 'email_pribadi' => 'b@primakara.test']),
    ]]);

    $run = runSiskaSync();

    expect($run->removed_count)->toBe(0)
        ->and($voted->fresh()->belongsToActivePeriod())->toBeTrue();
});

it('removes nobody when the pull returns far fewer students than the roll holds', function () {
    $roll = User::factory()->count(6)->create();

    fakeSiska([[siskaStudent(['nim' => '2301010001', 'email_pribadi' => 'a@primakara.test'])]]);

    $run = runSiskaSync();

    expect($run->status)->toBe(VoterSyncRun::STATUS_FAILED)
        ->and($run->error)->toContain('Dihentikan sebelum menghapus')
        ->and($run->removed_count)->toBe(0);

    // Everyone who was already entitled to vote still is.
    foreach ($roll as $voter) {
        expect($voter->fresh()->belongsToActivePeriod())->toBeTrue();
    }
});

it('reports a student whose student number disagrees with their programme', function () {
    // NIM says 0101 (Sistem Informasi), SISKA says Teknik Informatika.
    fakeSiska([[siskaStudent(['nim' => '2301010001', 'prodi' => ['kode_prodi' => 'S1-TI']])]]);

    $run = runSiskaSync();

    expect($run->status)->toBe(VoterSyncRun::STATUS_COMPLETED)
        // Still enrolled: they can vote for BEM and DPM regardless.
        ->and($run->enrolled_count)->toBe(1)
        ->and(collect($run->issues)->pluck('reason')->implode(' '))->toContain('tidak cocok dengan prodi di SISKA');
});

it('leaves the roll untouched and reports the failure when SISKA errors', function () {
    $roll = User::factory()->count(3)->create();

    Http::fake(['siska.test/api/v1/mahasiswa*' => Http::response(['message' => 'API key tidak valid'], 401)]);

    $run = VoterSyncRun::create([
        'period_id' => Period::activeId(),
        'status' => VoterSyncRun::STATUS_RUNNING,
        'started_at' => now(),
    ]);

    $job = new ImportSiskaStudentPage($run->id);

    expect(fn () => $job->handle(
        app(App\Services\SiskaService::class),
        app(App\Services\VoterRollSynchroniser::class),
    ))->toThrow(RuntimeException::class);

    // The queue calls failed() once the retries are spent.
    $job->failed(new RuntimeException('SISKA menolak permintaan daftar mahasiswa (HTTP 401).'));

    expect($run->fresh()->status)->toBe(VoterSyncRun::STATUS_FAILED)
        ->and(Period::active()->voters()->count())->toBe(3);

    foreach ($roll as $voter) {
        expect($voter->fresh()->belongsToActivePeriod())->toBeTrue();
    }
});

it('is idempotent: pulling twice changes nothing the second time', function () {
    fakeSiska([[
        siskaStudent(['nim' => '2301010001', 'email_pribadi' => 'a@primakara.test']),
        siskaStudent(['nim' => '2301010002', 'email_pribadi' => 'b@primakara.test']),
    ]]);

    runSiskaSync();

    $second = runSiskaSync();

    expect($second->status)->toBe(VoterSyncRun::STATUS_COMPLETED)
        ->and($second->created_count)->toBe(0)
        ->and($second->updated_count)->toBe(0)
        ->and($second->enrolled_count)->toBe(0)
        ->and($second->removed_count)->toBe(0)
        ->and(User::query()->count())->toBe(2)
        ->and(Period::active()->voters()->count())->toBe(2);
});

it('asks SISKA only for active students, in a stable order', function () {
    fakeSiska([[siskaStudent()]]);

    runSiskaSync();

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'id_ref_status_mahasiswa=1')
            && str_contains($request->url(), 'sort_by=nim')
            && $request->header('X-API-KEY')[0] === 'siska-key';
    });
});

it('queues the pull instead of doing it in the request', function () {
    Queue::fake();

    $run = VoterSyncRun::create([
        'period_id' => Period::activeId(),
        'status' => VoterSyncRun::STATUS_RUNNING,
        'started_at' => now(),
    ]);

    ImportSiskaStudentPage::dispatch($run->id);

    Queue::assertPushed(ImportSiskaStudentPage::class);
});

it('shows the run and its notes on the admin history page', function () {
    $this->actingAs(User::factory()->admin()->create());

    fakeSiska([[
        siskaStudent(['nim' => '2301010001', 'email_pribadi' => 'a@primakara.test', 'prodi' => ['kode_prodi' => 'S1-TI']]),
    ]]);

    $run = runSiskaSync();

    expect($run->issues)->not->toBeEmpty();

    Livewire::test(ListVoterSyncRuns::class)
        ->assertCanSeeTableRecords([$run])
        // The notes live in the detail modal, which renders the infolist.
        ->callAction(TestAction::make('view')->table($run))
        ->assertHasNoActionErrors();
});

it('starts a run from the voters page and refuses to start a second one', function () {
    Queue::fake();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(ListVoters::class)
        ->callAction('syncFromSiska')
        ->assertHasNoActionErrors();

    expect(VoterSyncRun::query()->count())->toBe(1)
        ->and(VoterSyncRun::isRunning())->toBeTrue();

    Queue::assertPushed(ImportSiskaStudentPage::class, 1);

    // Pressing it again while one is in flight must not start a rival run.
    Livewire::test(ListVoters::class)->callAction('syncFromSiska');

    expect(VoterSyncRun::query()->count())->toBe(1);
    Queue::assertPushed(ImportSiskaStudentPage::class, 1);
});

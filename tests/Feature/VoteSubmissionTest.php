<?php

use App\Models\Candidate;
use App\Models\Config;
use App\Models\Event;
use App\Models\Prodi;
use App\Models\User;
use App\Models\UserVote;
use App\Models\Vote;

/**
 * Builds a university-wide election plus one election per study programme,
 * then returns a voter registered in $prodiCode.
 */
function ballotScenario(string $prodiCode = '0101', string $otherProdiCode = '0202'): array
{
    Prodi::factory()->create(['id_prodi' => $prodiCode]);
    Prodi::factory()->create(['id_prodi' => $otherProdiCode]);

    $bem = Event::factory()->universityWide()->create(['title' => 'Presiden Mahasiswa']);
    $ownHima = Event::factory()->forProdi($prodiCode)->create(['title' => 'HIMA Sendiri']);
    $otherHima = Event::factory()->forProdi($otherProdiCode)->create(['title' => 'HIMA Lain']);

    $candidates = [
        'bem' => Candidate::factory()->count(2)->create(['event_id' => $bem->id]),
        'own' => Candidate::factory()->count(2)->create(['event_id' => $ownHima->id]),
        'other' => Candidate::factory()->count(2)->create(['event_id' => $otherHima->id]),
    ];

    $voter = User::factory()->inProdi($prodiCode)->create();
    UserVote::create(['user_id' => $voter->id, 'status' => UserVote::STATUS_NOT_VOTED]);

    openVotingWindow();

    return compact('bem', 'ownHima', 'otherHima', 'candidates', 'voter');
}

function openVotingWindow(): void
{
    Config::set(Config::VOTING_STARTS_AT, now()->subDay()->format('Y-m-d H:i:s'));
    Config::set(Config::VOTING_ENDS_AT, now()->addDay()->format('Y-m-d H:i:s'));
}

function closeVotingWindow(): void
{
    Config::set(Config::VOTING_STARTS_AT, now()->subDays(5)->format('Y-m-d H:i:s'));
    Config::set(Config::VOTING_ENDS_AT, now()->subDays(4)->format('Y-m-d H:i:s'));
}

/** A valid, complete ballot for the scenario's voter. */
function validBallot(array $scenario): array
{
    return [
        ['event_id' => $scenario['bem']->id, 'candidate_id' => $scenario['candidates']['bem'][0]->id],
        ['event_id' => $scenario['ownHima']->id, 'candidate_id' => $scenario['candidates']['own'][0]->id],
    ];
}

it('accepts a complete, well formed ballot exactly once', function () {
    $scenario = ballotScenario();

    $this->actingAs($scenario['voter'])
        ->post(route('vote.submit'), ['votes' => validBallot($scenario)])
        ->assertRedirect(route('dashboard'));

    expect(Vote::count())->toBe(2)
        ->and($scenario['voter']->fresh()->userVote->status)->toBe(UserVote::STATUS_VOTED);
});

it('rejects ballot stuffing through repeated entries for the same election', function () {
    $scenario = ballotScenario();

    // The original code looped over the array without deduplicating, so this
    // single request would have inserted 500 votes for one candidate.
    $stuffed = array_fill(0, 500, [
        'event_id' => $scenario['bem']->id,
        'candidate_id' => $scenario['candidates']['bem'][0]->id,
    ]);

    $this->actingAs($scenario['voter'])
        ->post(route('vote.submit'), ['votes' => $stuffed])
        ->assertSessionHasErrors('votes');

    expect(Vote::count())->toBe(0)
        ->and($scenario['voter']->fresh()->userVote->status)->toBe(UserVote::STATUS_NOT_VOTED);
});

it('rejects voting in another study programme election', function () {
    $scenario = ballotScenario();

    $ballot = validBallot($scenario);
    $ballot[] = [
        'event_id' => $scenario['otherHima']->id,
        'candidate_id' => $scenario['candidates']['other'][0]->id,
    ];

    $this->actingAs($scenario['voter'])
        ->post(route('vote.submit'), ['votes' => $ballot])
        ->assertSessionHasErrors('votes');

    expect(Vote::count())->toBe(0);
});

it('rejects a candidate who does not stand in the submitted election', function () {
    $scenario = ballotScenario();

    $ballot = [
        // A candidate from the other programme's election, filed under BEM.
        ['event_id' => $scenario['bem']->id, 'candidate_id' => $scenario['candidates']['other'][0]->id],
        ['event_id' => $scenario['ownHima']->id, 'candidate_id' => $scenario['candidates']['own'][0]->id],
    ];

    $this->actingAs($scenario['voter'])
        ->post(route('vote.submit'), ['votes' => $ballot])
        ->assertSessionHasErrors();

    expect(Vote::count())->toBe(0);
});

it('rejects a partial ballot that would burn the remaining voting rights', function () {
    $scenario = ballotScenario();

    $this->actingAs($scenario['voter'])
        ->post(route('vote.submit'), [
            'votes' => [['event_id' => $scenario['bem']->id, 'candidate_id' => $scenario['candidates']['bem'][0]->id]],
        ])
        ->assertSessionHasErrors('votes');

    expect(Vote::count())->toBe(0)
        ->and($scenario['voter']->fresh()->userVote->status)->toBe(UserVote::STATUS_NOT_VOTED);
});

it('refuses a second ballot from a voter who already voted', function () {
    $scenario = ballotScenario();

    $this->actingAs($scenario['voter'])->post(route('vote.submit'), ['votes' => validBallot($scenario)]);
    expect(Vote::count())->toBe(2);

    // The middleware redirects a voter whose ballot is already cast.
    $this->actingAs($scenario['voter'])
        ->post(route('vote.submit'), ['votes' => validBallot($scenario)])
        ->assertRedirect(route('dashboard'));

    expect(Vote::count())->toBe(2);
});

it('blocks voting outside the configured window', function () {
    $scenario = ballotScenario();
    closeVotingWindow();

    $this->actingAs($scenario['voter'])
        ->get(route('voting'))
        ->assertRedirect(route('dashboard'));

    $this->actingAs($scenario['voter'])
        ->post(route('vote.submit'), ['votes' => validBallot($scenario)])
        ->assertRedirect(route('dashboard'));

    expect(Vote::count())->toBe(0);
});

it('requires authentication to submit a ballot', function () {
    $scenario = ballotScenario();

    $this->post(route('vote.submit'), ['votes' => validBallot($scenario)])
        ->assertRedirect(route('home'));

    expect(Vote::count())->toBe(0);
});

it('only offers the voter their own eligible elections', function () {
    $scenario = ballotScenario();

    $this->actingAs($scenario['voter'])
        ->get(route('voting'))
        ->assertInertia(fn ($page) => $page
            ->component('Voting')
            ->has('events', 2)
            ->where('events.0.id', $scenario['bem']->id)
            ->where('events.1.id', $scenario['ownHima']->id)
        );
});

it('coarsens vote timestamps so a ballot cannot be reassembled per voter', function () {
    $scenario = ballotScenario();

    $this->actingAs($scenario['voter'])->post(route('vote.submit'), ['votes' => validBallot($scenario)]);

    Vote::all()->each(function (Vote $vote) {
        expect($vote->voted_at->minute)->toBe(0)
            ->and($vote->voted_at->second)->toBe(0);
    });
});

it('never exposes a voter password through the shared auth props', function () {
    $scenario = ballotScenario();

    $this->actingAs($scenario['voter'])
        ->get(route('dashboard'))
        ->assertInertia(fn ($page) => $page
            ->missing('auth.user.password')
            ->missing('user.password')
        );
});

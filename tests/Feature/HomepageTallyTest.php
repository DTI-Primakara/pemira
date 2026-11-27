<?php

use App\Models\Period;
use App\Models\Candidate;
use App\Models\Event;
use App\Models\Prodi;
use App\Models\Vote;

it('hides the tally entirely while the admin toggle is off', function () {
    Period::active()->update(['is_vote_count_visible' => false]);

    $event = Event::factory()->universityWide()->create();
    $candidate = Candidate::factory()->create(['event_id' => $event->id]);

    Vote::insert([[
        'event_id' => $event->id,
        'candidate_id' => $candidate->id,
        'voted_at' => now()->startOfHour(),
        'created_at' => now()->startOfHour(),
        'updated_at' => now()->startOfHour(),
    ]]);

    $response = $this->get(route('home'));

    // The counts must not merely be unrendered: they must never reach the page.
    $response->assertInertia(fn ($page) => $page
        ->component('Homepage')
        ->where('voteCountVisible', false)
        ->where('groups', [])
    );

    $response->assertDontSee('votes_count');
});

it('publishes aggregate counts but never individual vote rows', function () {
    Period::active()->update(['is_vote_count_visible' => true]);

    $event = Event::factory()->universityWide()->create(['title' => 'Presiden Mahasiswa']);
    $candidate = Candidate::factory()->create(['event_id' => $event->id, 'number' => 1]);

    Vote::insert(collect(range(1, 3))->map(fn () => [
        'event_id' => $event->id,
        'candidate_id' => $candidate->id,
        'voted_at' => now()->startOfHour(),
        'created_at' => now()->startOfHour(),
        'updated_at' => now()->startOfHour(),
    ])->all());

    $response = $this->get(route('home'));

    $response->assertInertia(fn ($page) => $page
        ->component('Homepage')
        ->where('voteCountVisible', true)
        ->where('groups.0.events.0.candidates.0.votes_count', 3)
        // Individual ballots carry timestamps that can be correlated per voter.
        ->missing('groups.0.events.0.candidates.0.votes')
    );
});

it('includes newly created elections without any code change', function () {
    Period::active()->update(['is_vote_count_visible' => true]);

    Prodi::factory()->create([
        'id_prodi' => '0301',
        'faculty' => 'Fakultas Baru',
        'faculty_short' => 'FB',
    ]);

    $mpr = Event::factory()->create(['title' => 'Majelis Permusyawaratan Rakyat', 'type' => 'MPR', 'id_prodi' => null]);
    Candidate::factory()->create(['event_id' => $mpr->id]);

    $newHima = Event::factory()->forProdi('0301')->create(['title' => 'HIMA Prodi Baru']);
    Candidate::factory()->create(['event_id' => $newHima->id]);

    $this->get(route('home'))->assertInertia(fn ($page) => $page
        ->where('groups.0.events.0.title', 'Majelis Permusyawaratan Rakyat')
        ->where('groups.1.title', 'Fakultas Baru')
        ->where('groups.1.events.0.title', 'HIMA Prodi Baru')
    );
});

it('leaves out elections that have no candidates yet', function () {
    Period::active()->update(['is_vote_count_visible' => true]);

    Event::factory()->universityWide()->create(['title' => 'Belum Ada Paslon']);

    $this->get(route('home'))->assertInertia(fn ($page) => $page->where('groups', []));
});

it('builds the candidate showcase from the admin data', function () {
    Prodi::factory()->create([
        'id_prodi' => '0102',
        'name' => 'Teknik Informatika',
        'short_name' => 'IF',
    ]);

    $bem = Event::factory()->universityWide()->create(['title' => 'Presiden Mahasiswa', 'type' => 'BEM']);
    Candidate::factory()->create([
        'event_id' => $bem->id,
        'number' => 1,
        'name_candidates' => 'Paslon Satu',
        'image' => '/candidates/legacy.webp',
    ]);

    $hima = Event::factory()->forProdi('0102')->create(['title' => 'HIMA Prodi Informatika']);
    Candidate::factory()->create([
        'event_id' => $hima->id,
        'number' => 1,
        'name_candidates' => 'Paslon HIMA',
        // The shape written by an upload from the admin panel.
        'image' => 'paslon-images/uploaded.png',
    ]);

    $this->get(route('home'))->assertInertia(fn ($page) => $page
        ->component('Homepage')
        ->has('elections', 2)
        ->where('elections.0.shortLabel', 'BEM')
        ->where('elections.0.title', 'Presiden Mahasiswa')
        ->where('elections.0.eligibility', 'Terbuka untuk seluruh mahasiswa')
        ->where('elections.0.candidates.0.image_url', '/candidates/legacy.webp')
        ->where('elections.1.shortLabel', 'HIMA IF')
        ->where('elections.1.eligibility', 'Khusus mahasiswa Teknik Informatika')
        ->where('elections.1.candidates.0.image_url', fn ($url) => str_contains($url, '/storage/paslon-images/uploaded.png'))
    );
});

it('shows a brand new election in the showcase without touching the code', function () {
    $mpr = Event::factory()->create([
        'title' => 'Majelis Permusyawaratan Rakyat',
        'type' => 'MPR',
        'id_prodi' => null,
    ]);
    Candidate::factory()->create(['event_id' => $mpr->id, 'number' => 1]);

    $this->get(route('home'))->assertInertia(fn ($page) => $page
        ->has('elections', 1)
        ->where('elections.0.shortLabel', 'MPR')
        ->where('elections.0.title', 'Majelis Permusyawaratan Rakyat')
        ->where('elections.0.eligibility', 'Terbuka untuk seluruh mahasiswa')
    );
});

it('still lists an election whose candidate has no photo yet', function () {
    $event = Event::factory()->universityWide()->create();
    Candidate::factory()->create(['event_id' => $event->id, 'image' => '']);

    $this->get(route('home'))->assertInertia(fn ($page) => $page
        ->has('elections', 1)
        ->where('elections.0.candidates.0.image_url', null)
    );
});

it('keeps the showcase visible even while the tally is hidden', function () {
    Period::active()->update(['is_vote_count_visible' => false]);

    $event = Event::factory()->universityWide()->create();
    Candidate::factory()->create(['event_id' => $event->id]);

    $this->get(route('home'))->assertInertia(fn ($page) => $page
        ->has('elections', 1)
        ->where('groups', [])
        ->where('voteCountVisible', false)
    );
});

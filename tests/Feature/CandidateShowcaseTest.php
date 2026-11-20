<?php

use App\Models\Candidate;
use App\Models\Config;
use App\Models\Event;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

it('keeps a blank box out of the homepage showcase', function () {
    $event = Event::factory()->universityWide()->create();
    Candidate::factory()->create(['event_id' => $event->id, 'number' => 1, 'name_candidates' => 'Paslon Satu']);
    Candidate::factory()->blankBox()->create(['event_id' => $event->id, 'number' => 2]);

    $this->withoutVite()
        ->get(route('home'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('elections.0.candidates', 1)
            ->where('elections.0.candidates.0.name_candidates', 'Paslon Satu')
        );
});

it('still counts votes cast for a blank box in the tally', function () {
    Config::set(Config::VOTE_COUNT_VISIBLE, 'true');

    $event = Event::factory()->universityWide()->create();
    Candidate::factory()->create(['event_id' => $event->id, 'number' => 1]);
    $blankBox = Candidate::factory()->blankBox()->create(['event_id' => $event->id, 'number' => 2]);

    Vote::insert([[
        'event_id' => $event->id,
        'candidate_id' => $blankBox->id,
        'voted_at' => now()->startOfHour(),
        'created_at' => now()->startOfHour(),
        'updated_at' => now()->startOfHour(),
    ]]);

    $this->withoutVite()
        ->get(route('home'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('groups.0.events.0.candidates', 2)
            ->where('groups.0.events.0.candidates.1.votes_count', 1)
        );
});

it('drops an election from the showcase when only a blank box stands', function () {
    $event = Event::factory()->universityWide()->create();
    Candidate::factory()->blankBox()->create(['event_id' => $event->id, 'number' => 1]);

    $this->withoutVite()
        ->get(route('home'))
        ->assertInertia(fn (AssertableInertia $page) => $page->has('elections', 0));
});

it('shows the homepage artwork when one has been uploaded', function () {
    $event = Event::factory()->universityWide()->create();
    Candidate::factory()->create([
        'event_id' => $event->id,
        'image' => 'paslon-images/plain.png',
        'showcase_image' => 'paslon-showcase/designed.png',
    ]);

    $this->withoutVite()
        ->get(route('home'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('elections.0.candidates.0.image_url', fn (string $url) => str_contains($url, 'paslon-showcase/designed.png'))
        );
});

it('falls back to the ballot photo while no homepage artwork exists', function () {
    $event = Event::factory()->universityWide()->create();
    Candidate::factory()->create([
        'event_id' => $event->id,
        'image' => 'paslon-images/plain.png',
        'showcase_image' => null,
    ]);

    $this->withoutVite()
        ->get(route('home'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('elections.0.candidates.0.image_url', fn (string $url) => str_contains($url, 'paslon-images/plain.png'))
        );
});

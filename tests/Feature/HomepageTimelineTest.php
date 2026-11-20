<?php

use App\Models\Timeline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

beforeEach(function () {
    // The migration carries over the agenda that used to be hardcoded; each
    // test here states its own.
    Timeline::query()->delete();
});

it('serves the agenda from the database in the order the admin set', function () {
    Timeline::create(['title' => 'Kongres', 'starts_on' => '2026-01-17', 'position' => 3]);
    Timeline::create(['title' => 'Pendaftaran Kandidat', 'starts_on' => '2026-11-01', 'ends_on' => '2026-11-16', 'position' => 1]);
    Timeline::create(['title' => 'Voting', 'starts_on' => '2026-12-15', 'position' => 2]);

    $this->withoutVite()
        ->get('/')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('timelines', 3)
            ->where('timelines.0.title', 'Pendaftaran Kandidat')
            ->where('timelines.0.starts_on', '2026-11-01')
            ->where('timelines.0.ends_on', '2026-11-16')
            ->where('timelines.1.title', 'Voting')
            ->where('timelines.2.title', 'Kongres')
        );
});

it('sends a null end date for a single day agenda', function () {
    Timeline::create(['title' => 'Debat Kandidat', 'starts_on' => '2026-12-12', 'position' => 1]);

    $this->withoutVite()
        ->get('/')
        ->assertInertia(fn (AssertableInertia $page) => $page->where('timelines.0.ends_on', null));
});

it('serves an empty agenda when nothing has been scheduled', function () {
    $this->withoutVite()
        ->get('/')
        ->assertInertia(fn (AssertableInertia $page) => $page->has('timelines', 0));
});

it('keeps the agenda out of the admin panel for a participant', function () {
    $this->withoutVite()
        ->actingAs(App\Models\User::factory()->create())
        ->get('/admin/timelines')
        ->assertForbidden();
});

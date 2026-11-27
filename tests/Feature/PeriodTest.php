<?php

use App\Filament\Resources\Periods\Pages\ListPeriods;
use Filament\Actions\Testing\TestAction;
use App\Models\Candidate;
use App\Models\Event;
use App\Models\Period;
use App\Models\User;
use App\Models\UserVote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('starts with exactly one active period carrying the old settings', function () {
    expect(Period::query()->where('is_active', true)->count())->toBe(1)
        ->and(Period::active()->name)->toBe('Periode Awal');
});

it('keeps exactly one period active when another is activated', function () {
    $previous = Period::active();
    $next = Period::factory()->create(['name' => 'PEMIRA 2027']);

    $next->activate();

    expect(Period::query()->where('is_active', true)->count())->toBe(1)
        ->and(Period::active()->id)->toBe($next->id)
        ->and($previous->fresh()->is_active)->toBeFalse();
});

it('leaves the previous period\'s elections untouched and uncounted', function () {
    $previous = Period::active();
    $oldEvent = Event::factory()->universityWide()->create(['title' => 'BEM 2025']);
    Candidate::factory()->create(['event_id' => $oldEvent->id]);

    $current = Period::factory()->create(['name' => 'PEMIRA 2026', 'is_vote_count_visible' => true]);
    $current->activate();

    $newEvent = Event::factory()->universityWide()->create(['title' => 'BEM 2026']);
    Candidate::factory()->create(['event_id' => $newEvent->id]);

    // The old election is still in the database...
    expect($oldEvent->fresh()->period_id)->toBe($previous->id);

    // ...but the homepage only knows about the period being run now.
    $this->withoutVite()
        ->get(route('home'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('elections', 1)
            ->where('elections.0.title', 'BEM 2026')
            ->has('groups.0.events', 1)
        );
});

it('gives a voter a fresh ballot in a new period', function () {
    $voter = User::factory()->create();
    $voter->ballot()->update(['status' => UserVote::STATUS_VOTED]);

    $next = Period::factory()->create();
    $next->activate();
    $voter->periods()->attach($next->id);

    expect($voter->fresh()->ballot()->status)->toBe(UserVote::STATUS_NOT_VOTED)
        ->and(UserVote::query()->where('user_id', $voter->id)->count())->toBe(2);
});

it('offers no elections to a voter who is not on the active roll', function () {
    $event = Event::factory()->universityWide()->create();
    Candidate::factory()->create(['event_id' => $event->id]);

    $enrolled = User::factory()->create();
    $outsider = User::factory()->notEnrolled()->create();

    expect($enrolled->eligibleEvents())->toHaveCount(1)
        ->and($outsider->eligibleEvents())->toHaveCount(0);
});

it('sends a voter who is not on the roll back to the dashboard', function () {
    $event = Event::factory()->universityWide()->create();
    Candidate::factory()->create(['event_id' => $event->id]);

    $this->withoutVite()
        ->actingAs(User::factory()->notEnrolled()->create())
        ->get('/voting')
        ->assertRedirect(route('dashboard'));
});

it('lets an admin create and activate a period from the panel', function () {
    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(ListPeriods::class)
        ->callAction('create', data: [
            'name' => 'PEMIRA 2027',
            'starts_at' => '2027-01-10 09:00:00',
            'ends_at' => '2027-01-11 18:00:00',
            'is_vote_count_visible' => true,
        ])
        ->assertHasNoActionErrors();

    $created = Period::query()->where('name', 'PEMIRA 2027')->sole();

    // A new period is dormant until someone activates it.
    expect($created->is_active)->toBeFalse();

    Livewire::test(ListPeriods::class)
        ->callAction(TestAction::make('activate')->table($created));

    expect(Period::active()->id)->toBe($created->id)
        ->and(Period::active()->is_vote_count_visible)->toBeTrue();
});

it('refuses a closing time that falls before the opening time', function () {
    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(ListPeriods::class)
        ->callAction('create', data: [
            'name' => 'PEMIRA 2027',
            'starts_at' => '2027-01-10 09:00:00',
            'ends_at' => '2027-01-09 09:00:00',
        ])
        ->assertHasActionErrors(['ends_at']);
});

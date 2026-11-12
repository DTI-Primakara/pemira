<?php

use App\Filament\Pages\ManageVotingSettings;
use App\Models\Config;
use App\Models\User;
use Livewire\Livewire;

it('keeps the settings page away from participants', function () {
    $participant = User::factory()->create();

    $this->withoutVite()
        ->actingAs($participant)
        ->get('/admin/manage-voting-settings')
        ->assertForbidden();
});

it('lets an admin flip the tally visibility', function () {
    Config::set(Config::VOTE_COUNT_VISIBLE, 'false');
    Config::set(Config::VOTING_STARTS_AT, '2026-12-01 08:00:00');
    Config::set(Config::VOTING_ENDS_AT, '2026-12-02 17:00:00');

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(ManageVotingSettings::class)
        ->assertFormSet([Config::VOTE_COUNT_VISIBLE => false])
        ->fillForm([Config::VOTE_COUNT_VISIBLE => true])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(Config::voteCountVisible())->toBeTrue();
});

it('lets an admin move the voting window', function () {
    Config::set(Config::VOTING_STARTS_AT, '2026-12-01 08:00:00');
    Config::set(Config::VOTING_ENDS_AT, '2026-12-02 17:00:00');

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(ManageVotingSettings::class)
        ->fillForm([
            Config::VOTING_STARTS_AT => '2027-01-10 09:00:00',
            Config::VOTING_ENDS_AT => '2027-01-11 18:00:00',
            Config::VOTE_COUNT_VISIBLE => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(Config::votingStartsAt()->format('Y-m-d H:i'))->toBe('2027-01-10 09:00')
        ->and(Config::votingEndsAt()->format('Y-m-d H:i'))->toBe('2027-01-11 18:00');
});

it('refuses a closing time that falls before the opening time', function () {
    Config::set(Config::VOTING_STARTS_AT, '2026-12-01 08:00:00');
    Config::set(Config::VOTING_ENDS_AT, '2026-12-02 17:00:00');

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(ManageVotingSettings::class)
        ->fillForm([
            Config::VOTING_STARTS_AT => '2027-01-10 09:00:00',
            Config::VOTING_ENDS_AT => '2027-01-09 09:00:00',
            Config::VOTE_COUNT_VISIBLE => false,
        ])
        ->call('save')
        ->assertHasFormErrors([Config::VOTING_ENDS_AT]);
});

<?php

use App\Models\Classroom;
use App\Models\User;
use App\Models\UserVote;

it('adds members in bulk by student number and reports unknown ones', function () {
    $classroom = Classroom::factory()->create();

    $budi = User::factory()->create(['nim' => '2301010001']);
    $citra = User::factory()->create(['nim' => '2301010002']);

    $report = $classroom->addMembersByNim("2301010001\n2301010002, 9999999999");

    expect($report['added'])->toBe(2)
        ->and($report['missing'])->toBe(['9999999999'])
        ->and($budi->fresh()->class_id)->toBe($classroom->id)
        ->and($citra->fresh()->class_id)->toBe($classroom->id);
});

it('moves a student who already belonged to another class', function () {
    $old = Classroom::factory()->create();
    $new = Classroom::factory()->create();

    $student = User::factory()->create(['nim' => '2301010003', 'class_id' => $old->id]);

    $report = $new->addMembersByNim('2301010003');

    expect($report['moved'])->toBe(1)
        ->and($report['added'])->toBe(0)
        ->and($student->fresh()->class_id)->toBe($new->id);
});

it('promotes the appointed coordinator and demotes the previous one', function () {
    $classroom = Classroom::factory()->create();

    $first = User::factory()->create(['class_id' => $classroom->id]);
    $second = User::factory()->create(['class_id' => $classroom->id]);

    $classroom->appointKorti($first->id);
    expect($first->fresh()->roles)->toBe(User::ROLE_KORTI);

    $classroom->appointKorti($second->id);

    expect($first->fresh()->roles)->toBe(User::ROLE_PARTICIPANT)
        ->and($second->fresh()->roles)->toBe(User::ROLE_KORTI)
        ->and($classroom->fresh()->korti_user_id)->toBe($second->id);
});

it('keeps the coordinator page away from ordinary voters', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('koordinator'))
        ->assertRedirect(route('dashboard'));
});

it('keeps the coordinator page away from guests', function () {
    $this->get(route('koordinator'))->assertRedirect(route('home'));
});

it('shows the coordinator who in their class has voted', function () {
    $classroom = Classroom::factory()->create();

    $korti = User::factory()->create(['class_id' => $classroom->id, 'name' => 'Korti']);
    $voted = User::factory()->create(['class_id' => $classroom->id, 'name' => 'Sudah Memilih']);
    $notVoted = User::factory()->create(['class_id' => $classroom->id, 'name' => 'Belum Memilih']);

    // Somebody from a different class must never appear in the list.
    User::factory()->create(['class_id' => Classroom::factory()->create()->id, 'name' => 'Kelas Lain']);

    $classroom->appointKorti($korti->id);

    UserVote::create(['user_id' => $voted->id, 'status' => UserVote::STATUS_VOTED]);
    UserVote::create(['user_id' => $notVoted->id, 'status' => UserVote::STATUS_NOT_VOTED]);

    $this->actingAs($korti->fresh())
        ->get(route('koordinator'))
        ->assertInertia(fn ($page) => $page
            ->component('Koordinator')
            ->where('classroom.name', $classroom->name)
            ->where('summary.total', 3)
            ->where('summary.voted', 1)
            ->where('summary.notVoted', 2)
            ->has('members', 3)
            ->where('members.1.name', 'Korti')
            ->where('members.2.name', 'Sudah Memilih')
            ->where('members.2.has_voted', true)
            ->where('members.0.name', 'Belum Memilih')
            ->where('members.0.has_voted', false)
        );
});

it('never exposes the ballot contents to the coordinator', function () {
    $classroom = Classroom::factory()->create();
    $korti = User::factory()->create(['class_id' => $classroom->id]);
    $classroom->appointKorti($korti->id);

    $this->actingAs($korti->fresh())
        ->get(route('koordinator'))
        ->assertInertia(fn ($page) => $page
            ->missing('members.0.votes')
            ->missing('members.0.candidate_id')
        );
});

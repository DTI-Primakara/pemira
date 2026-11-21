<?php

use App\Filament\Resources\Classrooms\Pages\ManageClassroomMembers;
use App\Filament\Resources\Paslons\Pages\CreatePaslon;
use App\Models\Candidate;
use App\Models\Classroom;
use App\Models\Event;
use App\Models\User;
use Livewire\Livewire;

$adminPages = [
    '/admin/voters',
    '/admin/events',
    '/admin/paslons',
    '/admin/candidates',
    '/admin/classrooms',
    '/admin/prodis',
    '/admin/timelines',
    '/admin/periods',
];

it('serves every admin page to an admin', function (string $path) {
    $this->withoutVite()
        ->actingAs(User::factory()->admin()->create())
        ->get($path)
        ->assertSuccessful();
})->with($adminPages);

it('refuses every admin page to a participant', function (string $path) {
    $this->withoutVite()
        ->actingAs(User::factory()->create())
        ->get($path)
        ->assertForbidden();
})->with($adminPages);

it('refuses every admin page to a coordinator', function (string $path) {
    $classroom = Classroom::factory()->create();
    $korti = User::factory()->create(['class_id' => $classroom->id]);
    $classroom->appointKorti($korti->id);

    $this->withoutVite()
        ->actingAs($korti->fresh())
        ->get($path)
        ->assertForbidden();
})->with($adminPages);

it('enrols students and appoints a coordinator through the admin form', function () {
    $this->actingAs(User::factory()->admin()->create());

    $classroom = Classroom::factory()->create(['name' => 'SI-3A']);
    $student = User::factory()->create(['nim' => '2301010111']);

    Livewire::test(ManageClassroomMembers::class, ['record' => $classroom->getKey()])
        ->fillForm(['name' => 'SI-3A', 'bulk_nim' => '2301010111'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($student->fresh()->class_id)->toBe($classroom->id);

    Livewire::test(ManageClassroomMembers::class, ['record' => $classroom->getKey()])
        ->fillForm(['name' => 'SI-3A', 'korti_user_id' => $student->id])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($student->fresh()->roles)->toBe(User::ROLE_KORTI)
        ->and($classroom->fresh()->korti_user_id)->toBe($student->id);
});

it('creates a blank box option without a photo, vision or mission', function () {
    $this->actingAs(User::factory()->admin()->create());

    $event = Event::factory()->universityWide()->create(['title' => 'BEM']);

    Livewire::test(CreatePaslon::class)
        ->fillForm([
            'event_id' => $event->id,
            'is_blank_box' => true,
            'name_candidates' => 'Kotak Kosong',
            'number' => 2,
            'position' => 'Kotak Kosong',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $blankBox = Candidate::query()->where('event_id', $event->id)->sole();

    expect($blankBox->is_blank_box)->toBeTrue()
        ->and($blankBox->image)->toBeNull();
});

it('still demands a photo, vision and mission from a real candidate', function () {
    $this->actingAs(User::factory()->admin()->create());

    $event = Event::factory()->universityWide()->create();

    Livewire::test(CreatePaslon::class)
        ->fillForm([
            'event_id' => $event->id,
            'is_blank_box' => false,
            'name_candidates' => 'Paslon Satu',
            'number' => 1,
            'position' => 'Calon Ketua BEM',
        ])
        ->call('create')
        ->assertHasFormErrors(['vision', 'mission', 'image']);
});

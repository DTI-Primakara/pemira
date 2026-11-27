<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('home'));
});

it('tells a signed-in student who is not on the roll to contact the admin', function () {
    // Signing in through the SSO creates an account but enrols nobody, so this
    // is the state every unknown campus account lands in.
    $outsider = User::factory()->notEnrolled()->create();

    $this->actingAs($outsider)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard')
            ->where('enrolled', false)
            ->where('votingOpen', false)
        );
});

it('treats an enrolled voter as entitled to a ballot', function () {
    $voter = User::factory()->create();

    $this->actingAs($voter)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page->where('enrolled', true));
});

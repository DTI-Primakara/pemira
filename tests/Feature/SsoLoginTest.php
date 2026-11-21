<?php

use App\Models\Period;
use App\Models\User;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config()->set('services.sso', [
        'host' => 'https://sso.test',
        'client_id' => 'pemira-client',
        'client_secret' => 'pemira-secret',
        'redirect' => 'https://pemira.test/auth/callback',
        'timeout' => 15,
    ]);
});

/**
 * Stand in for the SSO: hand back a token, then the profile behind it.
 */
function fakeSso(array $profile, array $overrides = []): void
{
    Http::fake([
        'sso.test/oauth/token' => Http::response(['access_token' => 'access-token-123', 'token_type' => 'Bearer']),
        'sso.test/api/user/profile' => Http::response(['status' => 'success', 'data' => $profile]),
        ...$overrides,
    ]);
}

/** Walk the callback with a state that matches the one we stored. */
function completeSsoCallback(array $profile): \Illuminate\Testing\TestResponse
{
    fakeSso($profile);

    return test()
        ->withSession(['sso.state' => 'the-state'])
        ->get('/auth/callback?code=the-code&state=the-state');
}

it('sends a student to the SSO with a state tied to their session', function () {
    $response = $this->get(route('auth.redirect'));

    $location = $response->headers->get('Location');
    $state = session('sso.state');

    expect($state)->toBeString()->not->toBeEmpty()
        ->and($location)->toStartWith('https://sso.test/sso/oauth/authorize?')
        ->and($location)->toContain('client_id=pemira-client')
        ->and($location)->toContain('response_type=code')
        // A shared machine must not hand the next student the previous one's
        // still-open SSO session.
        ->and($location)->toContain('prompt=login')
        ->and($location)->toContain('state='.$state);
});

it('refuses a callback whose state does not match the session', function () {
    fakeSso(['name' => 'Attacker', 'email' => 'attacker@example.test']);

    $response = $this->withSession(['sso.state' => 'the-real-state'])
        ->get('/auth/callback?code=the-code&state=a-forged-state');

    $response->assertRedirect(route('home'));

    expect(auth()->check())->toBeFalse()
        ->and(User::query()->where('email', 'attacker@example.test')->exists())->toBeFalse();

    Http::assertNothingSent();
});

it('refuses a callback that carries no code', function () {
    $response = $this->withSession(['sso.state' => 'the-state'])
        ->get('/auth/callback?state=the-state');

    $response->assertRedirect(route('home'));
    expect(auth()->check())->toBeFalse();
});

it('reports a cancelled login instead of signing anyone in', function () {
    $response = $this->withSession(['sso.state' => 'the-state'])
        ->get('/auth/callback?error=access_denied&state=the-state');

    $response->assertRedirect(route('home'))->assertSessionHas('error');
    expect(auth()->check())->toBeFalse();
});

it('signs in an enrolled voter and leaves their roll membership alone', function () {
    $voter = User::factory()->create(['email' => 'rai@primakara.test', 'nim' => '2301020001']);

    $response = completeSsoCallback([
        'name' => 'Rai Wiradarma',
        'email' => 'rai@primakara.test',
        'nim' => '2301020001',
    ]);

    $response->assertRedirect(route('dashboard'));

    expect(auth()->id())->toBe($voter->id)
        ->and($voter->fresh()->name)->toBe('Rai Wiradarma')
        ->and($voter->fresh()->belongsToActivePeriod())->toBeTrue();
});

it('creates a local account for an unknown campus account without enrolling it', function () {
    $response = completeSsoCallback([
        'name' => 'Mahasiswa Baru',
        'email' => 'baru@primakara.test',
        'nim' => '2401020009',
    ]);

    $response->assertRedirect(route('dashboard'));

    $user = User::query()->where('email', 'baru@primakara.test')->sole();

    expect(auth()->id())->toBe($user->id)
        ->and($user->nim)->toBe('2401020009')
        ->and((int) $user->roles)->toBe(User::ROLE_PARTICIPANT)
        // Signing in says who you are; it does not grant a ballot.
        ->and($user->belongsToActivePeriod())->toBeFalse()
        ->and(Period::active()->voters()->count())->toBe(0);
});

it('matches a voter imported under a stale e-mail by their student number', function () {
    $voter = User::factory()->create(['email' => 'typo@primakara.test', 'nim' => '2301020002']);

    completeSsoCallback([
        'name' => 'Nama Benar',
        'email' => 'benar@primakara.test',
        'nim' => '2301020002',
    ]);

    expect(auth()->id())->toBe($voter->id)
        ->and(User::query()->count())->toBe(1)
        ->and($voter->fresh()->email)->toBe('benar@primakara.test');
});

it('keeps a login working when the SSO student number already belongs to someone else', function () {
    $other = User::factory()->create(['email' => 'lain@primakara.test', 'nim' => '2301020003']);
    $voter = User::factory()->create(['email' => 'rai@primakara.test', 'nim' => null]);

    completeSsoCallback([
        'name' => 'Rai Wiradarma',
        'email' => 'rai@primakara.test',
        'nim' => '2301020003',
    ]);

    expect(auth()->id())->toBe($voter->id)
        // The unique column is left as it is rather than failing the login.
        ->and($voter->fresh()->nim)->toBeNull()
        ->and($other->fresh()->nim)->toBe('2301020003');
});

it('never overwrites an admin role when its owner signs in again', function () {
    $admin = User::factory()->admin()->create(['email' => 'admin@primakara.test']);

    completeSsoCallback([
        'name' => 'Admin PEMIRA',
        'email' => 'admin@primakara.test',
        'nim' => null,
    ]);

    expect($admin->fresh()->isAdmin())->toBeTrue();
});

it('turns a refused authorisation code into a readable failure', function () {
    Http::fake([
        'sso.test/oauth/token' => Http::response(['error' => 'invalid_grant'], 400),
    ]);

    $response = $this->withSession(['sso.state' => 'the-state'])
        ->get('/auth/callback?code=stale-code&state=the-state');

    $response->assertRedirect(route('home'))->assertSessionHas('error');
    expect(auth()->check())->toBeFalse();
});

it('refuses a profile that carries no e-mail address', function () {
    fakeSso(['name' => 'Tanpa Email', 'email' => '']);

    $response = $this->withSession(['sso.state' => 'the-state'])
        ->get('/auth/callback?code=the-code&state=the-state');

    $response->assertRedirect(route('home'))->assertSessionHas('error');

    expect(auth()->check())->toBeFalse()
        ->and(User::query()->count())->toBe(0);
});

it('sends the student home when SSO credentials are missing from the server', function () {
    config()->set('services.sso.client_id', '');

    $response = $this->get(route('auth.redirect'));

    $response->assertRedirect(route('home'))->assertSessionHas('error');
    expect(session('sso.state'))->toBeNull();
});

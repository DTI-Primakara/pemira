<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SsoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Student sign-in through the campus SSO.
 *
 * Signing in says who someone is, not that they may vote: anyone with a campus
 * account gets a local row and reaches the dashboard, which then tells them
 * whether they are on this period's roll. Entitlement stays the admin's to
 * grant, per period.
 */
class SsoLoginController extends Controller
{
    /**
     * Session key holding the CSRF state for the authorisation round-trip.
     */
    private const STATE_KEY = 'sso.state';

    public function __construct(private readonly SsoService $sso) {}

    public function redirect(Request $request): RedirectResponse
    {
        if (! $this->sso->isConfigured()) {
            return redirect()->route('home')
                ->with('error', 'Login SSO belum dikonfigurasi pada server ini. Silakan hubungi admin.');
        }

        $state = Str::random(40);

        $request->session()->put(self::STATE_KEY, $state);

        return redirect()->away($this->sso->authorizeUrl($state));
    }

    public function callback(Request $request): RedirectResponse
    {
        // Pulled before anything can return early, so a failed attempt cannot
        // leave a state behind for a later callback to reuse.
        $expectedState = $request->session()->pull(self::STATE_KEY);

        if ($request->filled('error')) {
            return $this->fail($request, 'Login dibatalkan. Silakan coba lagi.');
        }

        $code = (string) $request->query('code', '');
        $state = (string) $request->query('state', '');

        // The state ties this callback to the redirect this browser started.
        // Without the check, anyone could hand a student a link carrying a code
        // of their own and sign them into somebody else's account.
        if ($code === '' || ! is_string($expectedState) || ! hash_equals($expectedState, $state)) {
            return $this->fail($request, 'Sesi login berakhir, silakan login kembali.');
        }

        $token = $this->sso->exchangeCodeForToken($code);

        if ($token === null) {
            return $this->fail($request, 'Tidak dapat memverifikasi login ke server SSO. Silakan coba lagi.');
        }

        $profile = $this->sso->fetchProfile($token);

        if ($profile === null) {
            return $this->fail($request, 'Data akun tidak dapat dibaca dari server SSO. Silakan hubungi admin.');
        }

        $user = User::syncFromSso($profile);

        Auth::login($user);

        // Prevent session fixation: the pre-login session id must not survive login.
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    private function fail(Request $request, string $message): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('error', $message);
    }
}

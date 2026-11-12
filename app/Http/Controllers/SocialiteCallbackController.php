<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialiteCallbackController extends Controller
{
    public function handleCallback(Request $request)
    {
        try {
            $socialUser = Socialite::driver('google')->user();
        } catch (InvalidStateException) {
            $this->resetSession($request);

            return redirect()->route('home')->with('error', 'Sesi login berakhir, silakan login kembali.');
        } catch (\Throwable) {
            $this->resetSession($request);

            return redirect()->route('home')->with('error', 'Terjadi kesalahan saat login. Silakan coba lagi.');
        }

        $email = $socialUser->getEmail();

        $pemiraUser = $email ? User::where('email', $email)->first() : null;

        if (! $pemiraUser) {
            $this->resetSession($request);

            return redirect()->route('home')->with('error', 'Maaf, akun Anda tidak terdaftar di sistem Pemira.');
        }

        Auth::login($pemiraUser);

        // Prevent session fixation: the pre-login session id must not survive login.
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    private function resetSession(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}

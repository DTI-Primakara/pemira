<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Socialite\Socialite;

class SocialiteCallbackController extends Controller
{
    public function handleCallback(Request $request)
    {
        try {
            // Gunakan stateless FALSE agar tetap pakai session
            $socialUser = Socialite::driver('google')->user();

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/auth/redirect')
                ->with('error', 'Sesi login berakhir, silakan login kembali.');
        } catch (\Exception $e) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')
                ->with('error', 'Terjadi kesalahan saat login. Silakan coba lagi.');
        }

        // Cari user berdasarkan email Google
        $pemiraUser = User::where('email', $socialUser->getEmail())->first();

        if (!$pemiraUser) {
            return redirect('/')
                ->with('error', 'Maaf, akun Anda tidak terdaftar di sistem Pemira.');
        }

        // Login user
        Auth::login($pemiraUser);

        // Pastikan data user dibagikan ke Inertia
        Inertia::share('auth.user', fn () => Auth::user());

        return redirect('/dashboard');
    }

}

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
        $socialUser = Socialite::driver('google')->user();

        $pemiraUser = User::where('email', $socialUser->getEmail())->first();

        if (!$pemiraUser) {
            return redirect('/')->with('error', 'Unauthorized user.');
        }

        Auth::login($pemiraUser);

        Inertia::share('auth.user', function () {
            return Auth::user();
        });
        return redirect('/dashboard');
    }
}

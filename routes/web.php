<?php

use App\Http\Controllers\Auth\SSOController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    if (session('user')) {
        return redirect()->route('dashboard');
    }
    return Inertia::render('Homepage', [
        'ssoUri' => env('SSO_URI', null),
        'clientId' => env('SSO_CLIENT_ID', null),
        'redirectUri' => env('SSO_REDIRECT_URI', null)
    ]);
})->name('home');

Route::get('/auth/callback', [SSOController::class, 'callback'])->name('auth.callback');

Route::middleware(['sso'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('history', function () { {
            return Inertia::render('History');
        }
    })->name('history');
    Route::get('kandidat', function () {
        return Inertia::render('Kandidat');
    })->name('kandidat');
    Route::get('guide', function () {
        return Inertia::render('TourGuide');
    })->name('guide');
    Route::get('voting', function () {
        return Inertia::render('Voting');
    })->name('voting');
});

Route::get('/logout', function () {
    session()->flush('user');
    return redirect()->route('home');
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

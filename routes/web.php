<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogoutUserController;
use App\Http\Controllers\SocialiteCallbackController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Middleware\CheckVoterStatus;
use App\Http\Middleware\OauthLoginCheck;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Socialite;

Route::get('/', [DashboardController::class, 'showHomepage'])->name('home');

Route::prefix('/auth')->group(function () {
    Route::get('/redirect', function () {
        return Socialite::driver('google')->redirect();
    });
    Route::get('/callback', [SocialiteCallbackController::class, 'handleCallback']);
});


Route::middleware(OauthLoginCheck::class)->group(function () {
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    Route::get('history', function () { {
            return Inertia::render('History');
        }
    })->name('history');

    Route::get('kandidat', function () {
        return Inertia::render('Kandidat');
    })->name('kandidat');

    Route::middleware(CheckVoterStatus::class)->group(function () {
        Route::get('guide', function () {
            return Inertia::render('TourGuide');
        })->name('guide');

        Route::get('voting', [VoteController::class, 'getCandidates'])->name('voting');
    });

    Route::post('/vote/submit', [VoteController::class, 'submitVote'])
        ->name('vote.submit');
});

Route::get('/logout', [LogoutUserController::class, 'logout']);


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogoutUserController;
use App\Http\Controllers\SocialiteCallbackController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Middleware\CheckVoterStatus;
use App\Http\Middleware\OauthLoginCheck;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', [DashboardController::class, 'showHomepage'])->name('home');

Route::get('/closing', fn () => Inertia::render('Closing'))->name('closing');

Route::prefix('/auth')->group(function () {
    Route::get('/redirect', fn () => Socialite::driver('google')->redirect())->name('auth.redirect');
    Route::get('/callback', [SocialiteCallbackController::class, 'handleCallback'])->name('auth.callback');
});

Route::middleware(OauthLoginCheck::class)->group(function () {
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    Route::middleware(CheckVoterStatus::class)->group(function () {
        Route::get('guide', fn () => Inertia::render('TourGuide'))->name('guide');

        Route::get('voting', [VoteController::class, 'getCandidates'])->name('voting');

        Route::post('/vote/submit', [VoteController::class, 'submitVote'])->name('vote.submit');
    });
});

Route::post('/logout', [LogoutUserController::class, 'logout'])->name('logout');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

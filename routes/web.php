<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Homepage');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('history', function () {
    return Inertia::render('History');
})->middleware(['auth', 'verified'])->name('history');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('history', function () {{
        return Inertia::render('History');
    }})->name('history');
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


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $period = Period::active();

        return Inertia::render('Dashboard', [
            'user' => $user->only(['id', 'name', 'nim', 'email']),
            'status' => $user->ballot($period)->status,
            'votingOpen' => (bool) $period?->votingIsOpen() && $user->belongsToActivePeriod(),
            'votingEnded' => (bool) $period?->votingHasEnded(),
        ]);
    }
}

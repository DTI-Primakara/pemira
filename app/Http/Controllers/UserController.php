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

        // Anyone with a campus account can sign in, so the dashboard is where
        // someone who is not on this period's roll finds out. That outranks the
        // voting window: there is nothing for them to open.
        $enrolled = $user->belongsToActivePeriod();

        return Inertia::render('Dashboard', [
            'user' => $user->only(['id', 'name', 'nim', 'email']),
            'status' => $user->ballot($period)->status,
            'enrolled' => $enrolled,
            'votingOpen' => (bool) $period?->votingIsOpen() && $enrolled,
            'votingEnded' => (bool) $period?->votingHasEnded(),
        ]);
    }
}

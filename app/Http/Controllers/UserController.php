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
            // The signed-in user is already shared on every response as
            // `auth.user`; sending a second copy here is what let the sidebar
            // come to depend on a prop only this one page provided.
            'status' => $user->ballot($period)->status,
            'enrolled' => $enrolled,
            'votingOpen' => (bool) $period?->votingIsOpen() && $enrolled,
            'votingEnded' => (bool) $period?->votingHasEnded(),
        ]);
    }
}

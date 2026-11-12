<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        return Inertia::render('Dashboard', [
            'user' => $user->only(['id', 'name', 'nim', 'email']),
            'status' => $user->ballot()->status,
            'votingOpen' => Config::votingIsOpen(),
            'votingEnded' => Config::votingHasEnded(),
        ]);
    }
}

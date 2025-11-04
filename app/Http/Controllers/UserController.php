<?php

namespace App\Http\Controllers;

use App\Models\EligibleUser;
use App\Models\UserVote;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function getEligibleUser(string $nim)
    {
        return EligibleUser::where('nim', $nim)->first();
    }

    public function dashboard()
    {

        $user = Auth::user();

        $status = UserVote::firstOrCreate(
            ['user_id' => $user->id],
            ['status' => 'not voted']
        )->status;

        return Inertia::render('Dashboard', [
            'user' => $user,
            'status' => $status,
        ]);
    }
}

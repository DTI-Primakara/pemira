<?php

namespace App\Http\Controllers;

use App\Models\EligibleUser;
use App\Models\UserVote;
use Carbon\Carbon;
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

        $now = Carbon::now('Asia/Makassar');

        $start = Carbon::create(2025, 12, 15, 10, 0, 0, 'Asia/Makassar');
        $end   = Carbon::create(2025, 12, 16, 16, 59, 59, 'Asia/Makassar');

        $votingOpen = $now->between($start, $end);
        $votingEnded = $now->gt($end);

        return Inertia::render('Dashboard', [
            'user' => $user,
            'status' => $status,
            'votingOpen' => $votingOpen,
            'votingEnded' => $votingEnded,
        ]);
    }
}

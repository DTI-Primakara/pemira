<?php

namespace App\Http\Middleware;

use App\Models\EligibleUser;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckVoterStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $now = Carbon::now('Asia/Makassar');

        $start = Carbon::create(2025, 12, 15, 10, 0, 0, 'Asia/Makassar');
        $end   = Carbon::create(2025, 12, 16, 16, 59, 59, 'Asia/Makassar');

        if ($now->lt($start) || $now->gt($end)) {
            return redirect()->route('dashboard');
        }

        $userVote = Auth::user()->userVote;

        if ($userVote->status !== 'not voted') {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}

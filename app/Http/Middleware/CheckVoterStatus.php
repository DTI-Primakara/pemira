<?php

namespace App\Http\Middleware;

use App\Models\Period;
use App\Models\UserVote;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckVoterStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $period = Period::active();

        if (! $period || ! $period->votingIsOpen()) {
            return redirect()->route('dashboard');
        }

        $user = Auth::user();

        // Only the people on this period's roll hold a ballot in it.
        if (! $user->belongsToActivePeriod()) {
            return redirect()->route('dashboard');
        }

        // Created on demand so reaching this route without passing through the
        // dashboard first cannot fail on a missing ballot row.
        if ($user->ballot($period)->status !== UserVote::STATUS_NOT_VOTED) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}

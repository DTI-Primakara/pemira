<?php

namespace App\Http\Middleware;

use App\Models\Config;
use App\Models\UserVote;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckVoterStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Config::votingIsOpen()) {
            return redirect()->route('dashboard');
        }

        // Created on demand so reaching this route without passing through the
        // dashboard first cannot fail on a missing ballot row.
        if (Auth::user()->ballot()->status !== UserVote::STATUS_NOT_VOTED) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\EligibleUser;
use App\Models\User;
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
        $user = Auth::user()->userVote;

        if ($user->status != 'not voted') {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}

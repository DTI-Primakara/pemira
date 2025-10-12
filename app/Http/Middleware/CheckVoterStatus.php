<?php

namespace App\Http\Middleware;

use App\Models\EligibleUser;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
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
        $nim = session('user')['data']['nim'];

        if (!$nim) {
            return redirect()->route('home');
        }

        $user = EligibleUser::where('nim', $nim)->first();

        if (!$user) {
            return redirect()->route('dashboard');
        }

        if ($user->status != 'not voted') {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}

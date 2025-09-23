<?php

namespace App\Http\Controllers;

use App\Models\EligibleUser;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function validateNIM(string $nim)
    {
        return EligibleUser::where('nim', $nim)->exists();
    }

    public function getEligibleUser(string $nim)
    {
        return EligibleUser::where('nim', $nim)->first();
    }

    public function dashboard()
    {
        $nim = session('user')['data']['nim'];

        if (!$nim) {
            return Inertia::render('InvalidNIM');
        }

        $user = $this->getEligibleUser($nim);
        if (!$user) {
            return Inertia::render('InvalidNIM');
        }

        return Inertia::render('Dashboard', [
            'user' => Auth::user(),
        ]);
    }
}

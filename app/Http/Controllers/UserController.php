<?php

namespace App\Http\Controllers;

use App\Models\EligibleUser;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function validateNIM(string $nim): bool
    {
        return EligibleUser::where('nim', $nim)->exists();
    }

    public function dashboard()
    {
        $nim = session('user')['data']['nim'];
        if ($nim == null) {
            return Inertia::render('InvalidNIM');
        }
        if (!$this->validateNIM($nim)) {
            return Inertia::render('InvalidNIM');
        }

        return Inertia::render('Dashboard', [
            'user' => Auth::user(),
        ]);
    }
}

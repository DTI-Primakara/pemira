<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class VoterImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $name = $row['name'] ?? $row['nama'] ?? null;
        $nim = $row['nim'] ?? null;
        $email = $row['email'] ?? null;

        if (!$name || !$nim || !$email) {
            return null;
        }

        if (User::where('nim', $nim)->exists()) {
            return null;
        }

        return new User([
            'name' => $name,
            'nim' => $nim,
            'email' => $email,
            'roles' => 1, 
            'password' => Hash::make(Str::random(10)),
        ]);
    }
}
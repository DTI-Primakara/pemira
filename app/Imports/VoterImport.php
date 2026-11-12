<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VoterImport implements SkipsEmptyRows, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    /**
     * @param  array<string, mixed>  $row
     */
    public function model(array $row): ?User
    {
        $name = trim((string) ($row['name'] ?? ''));
        $nim = trim((string) ($row['nim'] ?? ''));
        $email = trim((string) ($row['email'] ?? ''));

        if ($name === '' || $nim === '' || $email === '') {
            return null;
        }

        if (User::where('email', $email)->orWhere('nim', $nim)->exists()) {
            return null;
        }

        // Voters authenticate through Google only, so no password is ever set.
        // The previous import stored the literal string "mahasiswa123" for every
        // student, and the model's "hashed" cast was disabled at the time.
        return new User([
            'name' => $name,
            'nim' => $nim,
            'email' => $email,
            'roles' => User::ROLE_PARTICIPANT,
        ]);
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }
}

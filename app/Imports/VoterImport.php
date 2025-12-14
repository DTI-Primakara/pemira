<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class VoterImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $name  = trim($row['name'] ?? '');
        $nim   = trim($row['nim'] ?? '');
        $email = trim($row['email'] ?? '');

        if ($name === '' || $nim === '' || $email === '') {
            return null;
        }

        // if (
        //     User::where('email', $email)->exists() ||
        //     ($nim !== '' && User::where('nim', $nim)->exists())
        // ) {
        //     return null;
        // }

        return new User([
            'name'     => $name,
            'nim'      => $nim,
            'email'    => $email,
            'roles'    => 3,
            'password' => 'mahasiswa123',
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

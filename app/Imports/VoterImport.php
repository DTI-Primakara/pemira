<?php

namespace App\Imports;

use App\Models\Period;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VoterImport implements SkipsEmptyRows, ToCollection, WithChunkReading, WithHeadingRow
{
    /**
     * Imports enrol students into the period being run now. A student who
     * already has an account from an earlier period keeps that one account and
     * is simply added to this period's roll.
     *
     * @param  Collection<int, Collection<string, mixed>>  $rows
     */
    public function collection(Collection $rows): void
    {
        $periodId = Period::activeId();

        foreach ($rows as $row) {
            $name = trim((string) ($row['name'] ?? ''));
            $nim = trim((string) ($row['nim'] ?? ''));
            $email = trim((string) ($row['email'] ?? ''));

            if ($name === '' || $nim === '' || $email === '') {
                continue;
            }

            $user = User::query()
                ->where('email', $email)
                ->orWhere('nim', $nim)
                ->first();

            // Voters authenticate through Google only, so no password is ever set.
            $user ??= User::create([
                'name' => $name,
                'nim' => $nim,
                'email' => $email,
                'roles' => User::ROLE_PARTICIPANT,
            ]);

            if ($periodId) {
                $user->periods()->syncWithoutDetaching([$periodId]);
            }
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }
}

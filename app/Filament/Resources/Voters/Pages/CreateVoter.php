<?php

namespace App\Filament\Resources\Voters\Pages;

use App\Filament\Resources\Voters\VoterResource;
use App\Models\Period;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateVoter extends CreateRecord
{
    protected static string $resource = VoterResource::class;

    /** Whether the submitted student already had an account before this. */
    private bool $enrolledExistingAccount = false;

    /**
     * Adding a voter by hand means "put this student on the roll", not
     * "insert a row".
     *
     * Signing in through the campus SSO creates an account before the admin
     * ever enrols its owner, so by the time a student reports being
     * unregistered their account usually exists. Inserting again would hit the
     * unique index on e-mail or student number and fail the one action that is
     * supposed to fix their problem.
     */
    protected function handleRecordCreation(array $data): Model
    {
        // E-mail first, then student number — the same order SSO sign-in uses,
        // so both paths agree on which account belongs to whom.
        $user = User::query()->where('email', $data['email'])->first()
            ?: User::query()->where('nim', $data['nim'])->first();

        if (! $user) {
            return static::getModel()::create($data);
        }

        $this->enrolledExistingAccount = true;

        $changes = ['name' => $data['name']];

        // Both columns are unique, so a value already held by another account
        // is left alone. Roles and class are never touched: an admin or korti
        // appointment must not be undone by adding its owner as a voter.
        if ($user->nim !== $data['nim'] && ! User::query()->where('nim', $data['nim'])->whereKeyNot($user->getKey())->exists()) {
            $changes['nim'] = $data['nim'];
        }

        if ($user->email !== $data['email'] && ! User::query()->where('email', $data['email'])->whereKeyNot($user->getKey())->exists()) {
            $changes['email'] = $data['email'];
        }

        $user->forceFill($changes)->save();

        return $user;
    }

    /** A voter added by hand joins the period being run now. */
    protected function afterCreate(): void
    {
        $periodId = Period::activeId();

        if ($periodId) {
            $this->record->periods()->syncWithoutDetaching([$periodId]);
        }
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return $this->enrolledExistingAccount
            ? 'Akun sudah ada, didaftarkan sebagai pemilih periode ini'
            : 'Pemilih ditambahkan';
    }
}

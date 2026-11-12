<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Earlier voter imports wrote a literal shared password ("mahasiswa123")
     * into users.password while the model's "hashed" cast was commented out.
     * Voters sign in through Google only, so those values have no purpose and
     * are cleared. Anything that is already a real hash is left untouched.
     */
    public function up(): void
    {
        DB::table('users')
            ->whereNotNull('password')
            ->whereIn('roles', [User::ROLE_PARTICIPANT, User::ROLE_KORTI])
            ->select('id', 'password')
            ->orderBy('id')
            ->chunk(500, function ($users) {
                $ids = collect($users)
                    ->reject(fn ($user) => Hash::isHashed($user->password))
                    ->pluck('id');

                if ($ids->isNotEmpty()) {
                    DB::table('users')->whereIn('id', $ids)->update(['password' => null]);
                }
            });
    }

    public function down(): void
    {
        // Plaintext credentials are not restored.
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * users.roles is a foreign key into this table, and SSO sign-in now creates
     * an account on its own — so the lookup row for a plain participant has to
     * exist whether or not anyone remembered to run the seeder. Role 4 already
     * arrives this way; these three were left to RolesSeeder.
     *
     * insertOrIgnore, so an existing installation keeps the names it has.
     */
    public function up(): void
    {
        DB::table('roles')->insertOrIgnore([
            ['id_roles' => 1, 'nama_roles' => 'Super Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id_roles' => 2, 'nama_roles' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id_roles' => 3, 'nama_roles' => 'Peserta', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Left in place deliberately: dropping a role row would break every user
     * pointing at it. The rows are reference data, not this migration's to own.
     */
    public function down(): void
    {
        //
    }
};

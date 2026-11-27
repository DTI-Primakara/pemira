<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RolesSeeder::class);
        $this->call(ProdiSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(CandidatesSeeder::class);
        $this->call(ConfigSeeder::class);

        // Credentials come from the environment so they are never committed.
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');

        if (blank($adminEmail) || blank($adminPassword)) {
            $this->command?->warn('Skipping admin user: set ADMIN_EMAIL and ADMIN_PASSWORD first.');

            return;
        }

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => env('ADMIN_NAME', 'Administrator'),
                'password' => $adminPassword,
                'roles' => User::ROLE_ADMIN,
            ]
        );
    }
}

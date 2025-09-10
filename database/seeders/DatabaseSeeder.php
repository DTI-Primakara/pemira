<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RolesSeeder::class);

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@pemira.com',
            'password' => Hash::make('admin'),
            'roles' => 2,
        ]);

        User::factory()->create([
            'name' => 'test',
            'email' => 'test@pemira.com',
            'password' => Hash::make('test'),
            'roles' => null,
        ]);

    }
}

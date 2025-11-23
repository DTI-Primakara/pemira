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
        $this->call(ProdiSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(CandidatesSeeder::class);

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
            'roles' => 1,
        ]);

        User::factory()->create([
            'name' => 'Wiradarma',
            'nim' => '2401020049',
            'email' => 'wiradarma002@gmail.com',
            'password' => Hash::make('sapimandi'),
            'roles' => 1,
        ]);
    }
}

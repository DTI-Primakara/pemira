<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['nama_roles' => 'Super Admin']);
        Role::create(['nama_roles' => 'Admin']);
        Role::create(['nama_roles' => 'Panitia']);
    }
}

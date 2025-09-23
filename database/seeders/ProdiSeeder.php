<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prodis')->insert([
            ['name' => 'Sistem Informasi', 'id_prodi' => '0101'],
            ['name' => 'Teknik Informatika', 'id_prodi' => '0102'],
        ]);
    }
}

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
            ['name' => 'Sistem Informasi', 'id_prodi' => '0101', 'short_name' => 'SI'],
            ['name' => 'Teknik Informatika', 'id_prodi' => '0102', 'short_name' => 'IF'],
            ['name' => 'Sistem Informasi Akuntansi', 'id_prodi' => '0103', 'short_name' => 'SIA'],
            ['name' => 'Desain Komunikasi Visual', 'id_prodi' => '0104', 'short_name' => 'DKV'],
            ['name' => 'Manajemen', 'id_prodi' => '0201', 'short_name' => 'Manajemen'],
            ['name' => 'Akuntansi', 'id_prodi' => '0202', 'short_name' => 'Akuntansi'],
            ['name' => 'Bisnis Digital', 'id_prodi' => '0203', 'short_name' => 'BD'],
        ]);
    }
}

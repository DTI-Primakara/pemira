<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            ['title' => 'Dewan Perwakilan Mahasiswa', 'type' => 'DPM', 'id_prodi' => null],
            ['title' => 'Presiden dan Wakil Presiden Mahasiswa', 'type' => 'BEM', 'id_prodi' => null],
            ['title' => 'HIMA Prodi Sistem Informasi', 'type' => 'HIMA', 'id_prodi' => '0101'],
            ['title' => 'HIMA Prodi Informatika', 'type' => 'HIMA', 'id_prodi' => '0102'],
            ['title' => 'HIMA Prodi Sistem Informasi Akuntansi', 'type' => 'HIMA', 'id_prodi' => '0103'],
            ['title' => 'HIMA Prodi Desain Komunikasi Visual', 'type' => 'HIMA', 'id_prodi' => '0104'],
            ['title' => 'HIMA Prodi Manajemen', 'type' => 'HIMA', 'id_prodi' => '0201'],
            ['title' => 'HIMA Prodi Akuntansi', 'type' => 'HIMA', 'id_prodi' => '0202'],
            ['title' => 'HIMA Prodi Bisnis Digital', 'type' => 'HIMA', 'id_prodi' => '0203'],
        ]);
    }
}

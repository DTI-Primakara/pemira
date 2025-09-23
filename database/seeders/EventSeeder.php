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
            ['title' => 'Presiden dan Wakil Presiden Mahasiswa', 'type' => 'PRESMA', 'id_prodi' => null],
            ['title' => 'HIMA Sistem Informasi', 'type' => 'HIMA', 'id_prodi' => '0101'],
            ['title' => 'HIMA Informatika', 'type' => 'HIMA', 'id_prodi' => '0102'],
            ['title' => 'HIMA Sistem Informasi Akuntansi', 'type' => 'HIMA', 'id_prodi' => '0103'],
            ['title' => 'HIMA Desain Komunikasi Visual', 'type' => 'HIMA', 'id_prodi' => '0104'],
            ['title' => 'HIMA Manajemen', 'type' => 'HIMA', 'id_prodi' => '0201'],
            ['title' => 'HIMA Akuntansi', 'type' => 'HIMA', 'id_prodi' => '0202'],
            ['title' => 'HIMA Bisnis Digital', 'type' => 'HIMA', 'id_prodi' => '0203'],
        ]);
    }
}

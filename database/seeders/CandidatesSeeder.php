<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('candidates')->insert([
            ['event_id' => 1, 'name_candidates' => 'Dominic Nauval', 'number' => 1, 'vision' => 'testing', 'mission' => 'testing mision', 'image' => 'dpm_1.pmg', 'position' => 'Calon Ketua DPM',],
            ['event_id' => 1, 'name_candidates' => 'Made Gautama', 'number' => 2, 'vision' => 'testing', 'mission' => 'testing mision', 'image' => 'dpm_2.pmg', 'position' => 'Calon Ketua DPM',],
            ['event_id' => 2, 'name_candidates' => 'Arya & Gautama', 'number' => 2, 'vision' => 'testing', 'mission' => 'testing mision', 'image' => 'bem_1.pmg', 'position' => 'Calon Ketua dan Wakil BEM',],
            ['event_id' => 2, 'name_candidates' => 'Abi & Ezy', 'number' => 2, 'vision' => 'testing', 'mission' => 'testing mision', 'image' => 'bem_2.pmg', 'position' => 'Calon Ketua dan Wakil BEM'],
            ['event_id' => 4, 'name_candidates' => 'Panji', 'number' => 1, 'vision' => 'testing', 'mission' => 'testing mision', 'image' => 'hima_if_1.pmg', 'position' => 'Calon Ketua HIMA IF',],
            ['event_id' => 4, 'name_candidates' => 'Steven', 'number' => 2, 'vision' => 'testing', 'mission' => 'testing mision', 'image' => 'hima_if_2.pmg', 'position' => 'Calon Ketua HIMA IF',],

        ]);
    }
}

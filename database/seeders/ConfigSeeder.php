<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('config')->insertOrIgnore([
            ['title' => Config::VOTE_COUNT_VISIBLE, 'value' => 'false', 'created_at' => now(), 'updated_at' => now()],
            ['title' => Config::VOTING_STARTS_AT, 'value' => '2025-12-15 10:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['title' => Config::VOTING_ENDS_AT, 'value' => '2025-12-16 16:59:59', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

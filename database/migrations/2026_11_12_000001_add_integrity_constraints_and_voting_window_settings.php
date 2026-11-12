<?php

use App\Models\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A voter may only ever have one ballot-status row; the previous code relied
        // on firstOrCreate alone, which races under concurrent requests.
        $this->deduplicateUserVotes();

        Schema::table('user_vote', function (Blueprint $table) {
            $table->unique('user_id');
        });

        // The original migration had a typo (->uniqiue()), which Laravel silently
        // accepted as a fluent attribute, so the index was never created.
        Schema::table('eligible_user', function (Blueprint $table) {
            $table->unique('nim');
        });

        Schema::table('config', function (Blueprint $table) {
            $table->unique('title');
        });

        // The voting window used to be hardcoded in two separate PHP files.
        DB::table('config')->insertOrIgnore([
            ['title' => Config::VOTING_STARTS_AT, 'value' => '2025-12-15 10:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['title' => Config::VOTING_ENDS_AT, 'value' => '2025-12-16 16:59:59', 'created_at' => now(), 'updated_at' => now()],
            ['title' => Config::VOTE_COUNT_VISIBLE, 'value' => 'false', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::table('user_vote', fn (Blueprint $table) => $table->dropUnique(['user_id']));
        Schema::table('eligible_user', fn (Blueprint $table) => $table->dropUnique(['nim']));
        Schema::table('config', fn (Blueprint $table) => $table->dropUnique(['title']));
    }

    private function deduplicateUserVotes(): void
    {
        $duplicateUserIds = DB::table('user_vote')
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('user_id');

        foreach ($duplicateUserIds as $userId) {
            $rows = DB::table('user_vote')->where('user_id', $userId)->orderBy('id')->get();

            // Keep a row that already records a cast ballot, otherwise keep the oldest.
            $keep = $rows->firstWhere('status', 'voted') ?? $rows->first();

            DB::table('user_vote')
                ->where('user_id', $userId)
                ->where('id', '!=', $keep->id)
                ->delete();
        }
    }
};

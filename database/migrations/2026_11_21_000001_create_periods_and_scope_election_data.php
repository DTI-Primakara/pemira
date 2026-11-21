<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Elections are now grouped into periods, exactly one of which is active.
     * Everything already in the database belongs to one initial period, so no
     * row is lost and nothing from an earlier election leaks into the next one.
     */
    public function up(): void
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_vote_count_visible')->default(false);
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->index('is_active');
        });

        // Which voters take part in which period. One account, many periods.
        Schema::create('period_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('periods')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['period_id', 'user_id']);
        });

        $periodId = $this->createInitialPeriod();

        $this->addPeriodColumn('events', $periodId, onDelete: 'restrict');
        $this->addPeriodColumn('timelines', $periodId, onDelete: 'cascade');
        $this->addPeriodColumn('user_vote', $periodId, onDelete: 'cascade');

        // A voter holds one ballot per period, not one ever. The composite
        // index goes in first: MySQL refuses to drop the single-column unique
        // while it is the only index backing the user_id foreign key.
        Schema::table('user_vote', function (Blueprint $table) {
            $table->unique(['user_id', 'period_id']);
        });

        Schema::table('user_vote', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
        });

        $this->enrolExistingVoters($periodId);
    }

    public function down(): void
    {
        Schema::table('user_vote', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'period_id']);
            $table->dropForeign(['period_id']);
            $table->dropColumn('period_id');
            $table->unique('user_id');
        });

        foreach (['events', 'timelines'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropForeign(['period_id']);
                $blueprint->dropColumn('period_id');
            });
        }

        Schema::dropIfExists('period_user');
        Schema::dropIfExists('periods');
    }

    /**
     * The period that inherits everything already in the database. It carries
     * the voting window that used to live in the config table.
     */
    private function createInitialPeriod(): int
    {
        $settings = DB::table('config')->pluck('value', 'title');

        return (int) DB::table('periods')->insertGetId([
            'name' => 'Periode Awal',
            'starts_at' => $settings['voting_starts_at'] ?? null,
            'ends_at' => $settings['voting_ends_at'] ?? null,
            'is_vote_count_visible' => ($settings['is_vote_count_visible'] ?? 'false') === 'true',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function addPeriodColumn(string $table, int $periodId, string $onDelete): void
    {
        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->unsignedBigInteger('period_id')->nullable()->index();
        });

        DB::table($table)->update(['period_id' => $periodId]);

        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->unsignedBigInteger('period_id')->nullable(false)->change();
        });

        Schema::table($table, function (Blueprint $blueprint) use ($onDelete) {
            $foreign = $blueprint->foreign('period_id')->references('id')->on('periods');

            $onDelete === 'cascade' ? $foreign->cascadeOnDelete() : $foreign->restrictOnDelete();
        });
    }

    /** Everyone who could vote before can still vote in the initial period. */
    private function enrolExistingVoters(int $periodId): void
    {
        $voters = DB::table('users')->whereIn('roles', [3, 4])->pluck('id');

        if ($voters->isEmpty()) {
            return;
        }

        DB::table('period_user')->insert($voters->map(fn (int $userId) => [
            'period_id' => $periodId,
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ])->all());
    }
};

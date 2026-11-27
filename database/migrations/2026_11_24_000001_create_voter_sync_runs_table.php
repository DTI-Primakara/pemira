<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * One row per "Tarik Data" run, kept as an audit trail: an election has
         * to be able to answer later how its roll came to hold who it holds.
         */
        Schema::create('voter_sync_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('periods')->cascadeOnDelete();
            // Who pressed the button. Nullable so deleting an admin account
            // never takes the audit trail with it.
            $table->foreignId('started_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('running');

            $table->unsignedInteger('current_page')->default(0);
            $table->unsignedInteger('last_page')->nullable();
            $table->unsignedInteger('total_students')->nullable();

            $table->unsignedInteger('created_count')->default(0);
            $table->unsignedInteger('updated_count')->default(0);
            $table->unsignedInteger('enrolled_count')->default(0);
            $table->unsignedInteger('skipped_count')->default(0);
            $table->unsignedInteger('seen_count')->default(0);
            $table->unsignedInteger('removed_count')->default(0);

            // Rows the run could not act on, each with the reason, so a student
            // who cannot vote is visible instead of silently missing.
            $table->json('issues')->nullable();
            $table->text('error')->nullable();

            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        /*
         * Stamped every time a run sees a student still active in SISKA. The
         * clean-up phase removes roll entries this run did not stamp, which is
         * what makes "no longer active" detectable without holding thousands of
         * ids in memory across queued jobs.
         */
        Schema::table('period_user', function (Blueprint $table) {
            $table->timestamp('synced_at')->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('period_user', function (Blueprint $table) {
            $table->dropColumn('synced_at');
        });

        Schema::dropIfExists('voter_sync_runs');
    }
};

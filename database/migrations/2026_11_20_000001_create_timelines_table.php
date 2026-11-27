<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The agenda shown on the homepage used to be hardcoded in the Vue
     * component. It moves here so the committee can edit it from the admin
     * panel; the dates are days, not instants, because the page only ever
     * renders a date.
     */
    public function up(): void
    {
        Schema::create('timelines', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('starts_on');
            $table->date('ends_on')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->index('position');
        });

        // Carry over the entries that were hardcoded in Timeline.vue so the
        // section is not empty right after deploying.
        $now = now();

        DB::table('timelines')->insert(collect([
            ['Pendaftaran Kandidat', '2025-11-01', '2025-11-16'],
            ['Kampanye Kandidat', '2025-12-11', null],
            ['Debat Presma & Wapresma', '2025-12-12', null],
            ['Voting Kandidat', '2025-12-15', '2025-12-16'],
            ['Laporan Pertanggung Jawaban', '2025-12-22', null],
            ['Pelantikan Kandidat Terpilih', '2026-01-10', null],
            ['Kongres', '2026-01-17', null],
        ])->map(fn (array $entry, int $index) => [
            'title' => $entry[0],
            'starts_on' => $entry[1],
            'ends_on' => $entry[2],
            'position' => $index + 1,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all());
    }

    public function down(): void
    {
        Schema::dropIfExists('timelines');
    }
};

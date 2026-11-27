<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Blank boxes were already entered as ordinary candidates named "Kotak
     * Kosong". Flag them so they drop out of the homepage showcase without the
     * committee having to find each one by hand; the toggle can still undo it.
     */
    public function up(): void
    {
        DB::table('candidates')
            ->whereRaw('LOWER(name_candidates) LIKE ?', ['%kotak kosong%'])
            ->update(['is_blank_box' => true]);
    }

    public function down(): void
    {
        DB::table('candidates')
            ->whereRaw('LOWER(name_candidates) LIKE ?', ['%kotak kosong%'])
            ->update(['is_blank_box' => false]);
    }
};

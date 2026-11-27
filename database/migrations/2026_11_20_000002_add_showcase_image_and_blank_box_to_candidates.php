<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Two photos per candidate: the plain one used on the ballot, and the
     * finished artwork shown in the homepage showcase. A blank box ("kotak
     * kosong") is a real ballot option, so it keeps its row and its votes, but
     * it has no photo, vision or mission to show.
     */
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('showcase_image')->nullable()->after('image');
            $table->boolean('is_blank_box')->default(false)->after('showcase_image');
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->longText('vision')->nullable()->change();
            $table->longText('mission')->nullable()->change();
            $table->string('image')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn(['showcase_image', 'is_blank_box']);
        });
    }
};

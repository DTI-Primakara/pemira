<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prodis', function (Blueprint $table) {
            $table->string('faculty')->nullable()->after('short_name');
            $table->string('faculty_short')->nullable()->after('faculty');
        });

        DB::table('prodis')->where('id_prodi', 'like', '01%')->update([
            'faculty' => 'Fakultas Teknologi Informasi dan Desain',
            'faculty_short' => 'FTID',
        ]);

        DB::table('prodis')->where('id_prodi', 'like', '02%')->update([
            'faculty' => 'Fakultas Ekonomi dan Bisnis',
            'faculty_short' => 'FEB',
        ]);

        // The type column was an ENUM('BEM','DPM','HIMA'), which meant a new kind of
        // election (MPR, and so on) could not be created from the admin panel at all.
        Schema::table('events', function (Blueprint $table) {
            $table->string('type')->default('HIMA')->change();
        });
    }

    public function down(): void
    {
        Schema::table('prodis', function (Blueprint $table) {
            $table->dropColumn(['faculty', 'faculty_short']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->enum('type', ['BEM', 'DPM', 'HIMA'])->change();
        });
    }
};

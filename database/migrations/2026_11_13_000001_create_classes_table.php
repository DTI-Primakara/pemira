<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            // The class coordinator ("korti"); nullable until one is appointed.
            $table->foreignId('korti_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->after('roles')->constrained('classes')->nullOnDelete();
        });

        DB::table('roles')->insertOrIgnore([
            ['id_roles' => 4, 'nama_roles' => 'Koordinator Kelas', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('class_id');
        });

        Schema::dropIfExists('classes');

        DB::table('roles')->where('id_roles', 4)->delete();
    }
};

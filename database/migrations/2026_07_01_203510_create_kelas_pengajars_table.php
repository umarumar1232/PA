<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelas_pengajar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->string('role')->default('co-teacher'); // owner, co-teacher
            $table->string('status')->default('pending'); // pending, accepted
            $table->timestamps();

            $table->unique(['mata_kuliah_id', 'user_id']);
        });

        // Seed existing classes to the first admin or teacher
        $adminUser = DB::table('users')->whereIn('role', ['admin', 'dosen'])->first() ?? DB::table('users')->first();
        if ($adminUser) {
            $classes = DB::table('mata_kuliahs')->get();
            foreach ($classes as $class) {
                DB::table('kelas_pengajar')->insertOrIgnore([
                    'mata_kuliah_id' => $class->id,
                    'user_id' => $adminUser->user_id,
                    'role' => 'owner',
                    'status' => 'accepted',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_pengajar');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->string('bagian')->nullable()->after('nama_mk');
            $table->string('tingkat')->nullable()->after('bagian');
            $table->string('mata_pelajaran')->nullable()->after('tingkat');
            $table->string('ruang')->nullable()->after('mata_pelajaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->dropColumn(['bagian', 'tingkat', 'mata_pelajaran', 'ruang']);
        });
    }
};

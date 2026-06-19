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
        Schema::create('ilbs', function (Blueprint $table) {
            $table->id('ilb_id');
            // Sama seperti dosen, menembak id_pengajar
            $table->foreignId('id_pengajar')->constrained('pengajars', 'id_pengajar')->onDelete('cascade');
            $table->string('spesialisasi'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ilbs');
    }
};

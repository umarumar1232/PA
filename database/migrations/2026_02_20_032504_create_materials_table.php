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
        Schema::create('materials', function (Blueprint $table) {
            $table->id(); 
            
            $table->foreignId('matakuliah_id')->constrained('mata_kuliahs')->onDelete('cascade'); 
            
            $table->string('title'); // judul materi
            $table->text('description')->nullable();
            
            // 2. Kolom tambahan dari file migrasi lama kamu (jika ada)
            $table->string('file')->nullable();
            $table->string('video_url')->nullable();
            
            // 3. Ganti colab_url menjadi template_code dengan tipe longText
            $table->longText('template_code')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};

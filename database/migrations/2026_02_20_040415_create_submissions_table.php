<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            
            // DIUBAH: Menambahkan target tabel 'users' dan kolom 'user_id' secara eksplisit
            $table->foreignId('user_id')->constrained('users', 'user_id')->cascadeOnDelete();

            $table->string('file')->nullable(); // kalau upload
            $table->integer('score')->nullable(); // nilai
            $table->enum('status', ['belum', 'sudah'])->default('belum');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }

};

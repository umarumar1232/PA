<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->string('type')->default('assignment')->after('material_id');
            $table->integer('points')->default(100)->after('deadline');
        });

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->json('options');
            $table->unsignedTinyInteger('correct_option')->default(0);
            $table->unsignedTinyInteger('points')->default(1);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->json('answers')->nullable()->after('file');
            $table->text('feedback')->nullable()->after('score');
            $table->timestamp('submitted_at')->nullable()->after('feedback');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['answers', 'feedback', 'submitted_at']);
        });

        Schema::dropIfExists('quiz_questions');

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn(['type', 'points']);
        });
    }
};

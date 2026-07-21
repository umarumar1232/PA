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
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            }
        });
        
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
        });
        
        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
        });
    }
};

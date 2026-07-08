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
        if (!Schema::hasColumn('submissions', 'link')) {
            Schema::table('submissions', function (Blueprint $table) {
                $table->string('link')->nullable()->after('file');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('submissions', 'link')) {
            Schema::table('submissions', function (Blueprint $table) {
                $table->dropColumn('link');
            });
        }
    }
};

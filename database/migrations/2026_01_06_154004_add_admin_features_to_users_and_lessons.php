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
        // 1. Add 'is_blocked' to Users if it doesn't exist
        if (!Schema::hasColumn('users', 'is_blocked')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_blocked')->default(false)->after('password');
            });
        }

        // 2. Add 'deleted_at' to Lessons (for Archiving) if it doesn't exist
        if (!Schema::hasColumn('lessons', 'deleted_at')) {
            Schema::table('lessons', function (Blueprint $table) {
                $table->softDeletes(); // Adds a 'deleted_at' column
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_blocked');
        });
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
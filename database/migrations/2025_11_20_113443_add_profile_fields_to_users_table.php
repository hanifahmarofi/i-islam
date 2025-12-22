<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            
            // The Logic: Only add the column if it DOES NOT exist
            if (!Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'age')) {
                $table->integer('age')->nullable();
            }
            if (!Schema::hasColumn('users', 'favourite_food')) {
                $table->string('favourite_food')->nullable();
            }
            if (!Schema::hasColumn('users', 'mother_name')) {
                $table->string('mother_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'father_name')) {
                $table->string('father_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'parents_phone')) {
                $table->string('parents_phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'total_points')) {
                $table->integer('total_points')->default(0);
            }
            if (!Schema::hasColumn('users', 'matric_id')) {
                $table->string('matric_id')->nullable();
            }
            // Important: This allows you to add the Role column safely too!
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('student');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['full_name', 'age', 'favourite_food', 'mother_name', 'father_name', 'parents_phone', 'total_points', 'matric_id', 'role'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
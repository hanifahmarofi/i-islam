<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('lessons', function (Blueprint $table) {
        // Add teacher_id column (nullable allows existing lessons to remain valid)
        $table->unsignedBigInteger('teacher_id')->nullable()->after('id');
        
        // Optional: Link it to the users table
        $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('lessons', function (Blueprint $table) {
        $table->dropForeign(['teacher_id']);
        $table->dropColumn('teacher_id');
    });
}
};

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
        // Add a JSON column to store multiple slide paths
        // We use 'nullable' so it doesn't break if no slides are uploaded
        $table->json('slides')->nullable()->after('teacher_id');
    });
}

public function down()
{
    Schema::table('lessons', function (Blueprint $table) {
        $table->dropColumn('slides');
    });
}
};

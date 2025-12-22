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
        // Add the missing 'xp' column
        $table->integer('xp')->default(100)->after('content');
    });
}

public function down()
{
    Schema::table('lessons', function (Blueprint $table) {
        $table->dropColumn('xp');
    });
}

    /**
     * Reverse the migrations.
     */
};

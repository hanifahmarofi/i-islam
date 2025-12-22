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
    Schema::create('live_quizzes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('teacher_id')->constrained('users');
        $table->string('title');
        $table->string('code')->unique();
        $table->dateTime('expires_at')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_quizzes');
    }
};

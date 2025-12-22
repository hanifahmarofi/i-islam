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
        Schema::create('live_quiz_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_quiz_id')->constrained()->onDelete('cascade'); // Links to the Quiz
            $table->foreignId('user_id')->constrained()->onDelete('cascade');      // Links to the Student
            $table->integer('score');           // Stores the score
            $table->integer('total_questions'); // Stores total questions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_quiz_results');
    }
};

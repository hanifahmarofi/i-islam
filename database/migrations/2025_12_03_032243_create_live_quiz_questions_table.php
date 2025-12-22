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
        Schema::create('live_quiz_questions', function (Blueprint $table) {
            $table->id();
            // --- The new code starts here ---
            $table->foreignId('live_quiz_id')->constrained()->onDelete('cascade');
            $table->string('question_text');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->string('correct_answer');
            // --- The new code ends here ---
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_quiz_questions');
    }
};

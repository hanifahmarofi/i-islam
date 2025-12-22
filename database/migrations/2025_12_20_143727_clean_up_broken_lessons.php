<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Lesson;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Option A: Delete any lesson where 'slides' is NULL
        Lesson::whereNull('slides')->delete();

        // Option B: Delete any lesson where 'slides' is literally "null" string or empty JSON "[]"
        // (Just in case some got saved weirdly)
        Lesson::where('slides', '[]')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We cannot "undelete" data easily, so we leave this empty.
        // This is a destructive cleanup operation.
    }
};
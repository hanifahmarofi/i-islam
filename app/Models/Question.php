<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
protected $fillable = [
        'lesson_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option'
    ];

    // Relationship: A Question belongs to a Lesson
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}

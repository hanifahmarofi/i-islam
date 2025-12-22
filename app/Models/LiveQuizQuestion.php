<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveQuizQuestion extends Model
{
    protected $fillable = [
    'live_quiz_id', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer'
];

}

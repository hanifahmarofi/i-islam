<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveQuizResult extends Model
{
    use HasFactory;

    protected $fillable = ['live_quiz_id', 'user_id', 'score', 'total_questions'];

    // Link back to the Student
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
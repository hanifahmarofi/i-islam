<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveQuiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'title',
        'code',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // --- ADD THIS PART TO FIX THE ERROR ---
    public function results()
    {
        return $this->hasMany(LiveQuizResult::class);
    }
    
    // Also add this if you want to access questions easily
    public function questions()
    {
        return $this->hasMany(LiveQuizQuestion::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonSlide extends Model
{
    use HasFactory;

    protected $fillable = ['lesson_id', 'image_path'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
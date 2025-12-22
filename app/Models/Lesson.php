<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    // 1. Allow these columns to be filled
    protected $fillable = [
        'title',
        'description',
        'content',
        'xp',
        'teacher_id',
        'slides', 
    ];

    // 2. Casts
    // Note: We removed 'slides' => 'collection' from here.
    // We are handling it in the 'getSlidesAttribute' function below instead.
    // This gives us more control to fix the "null" crash.
    protected $casts = [
        // 'slides' is handled manually now
    ];

    /**
     * 3. Custom Accessor for Slides
     * This function runs automatically whenever you access $lesson->slides
     */
    public function getSlidesAttribute($value)
    {
        // Check if the database value is null, empty, or just "[]"
        if (is_null($value) || $value === '' || $value === '[]') {
            // Return an empty Laravel Collection so ->count() works effectively (returns 0)
            return collect([]);
        }

        // If data exists, decode the JSON and wrap it in a Collection
        // "true" in json_decode ensures it returns an array, which collect() loves
        return collect(json_decode($value, true));
    }

    // 4. Relationships
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
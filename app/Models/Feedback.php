<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    // This line allows 'user_id' and 'rating' to be saved
    protected $fillable = ['user_id', 'rating'];
}
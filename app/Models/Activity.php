<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    // ADD 'type' to this list!
    protected $fillable = ['user_id', 'description', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
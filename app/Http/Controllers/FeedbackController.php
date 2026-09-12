<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Save the rating linked to the logged-in student
        Feedback::create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
        ]);

        return response()->json(['message' => 'Success']);
    }
}
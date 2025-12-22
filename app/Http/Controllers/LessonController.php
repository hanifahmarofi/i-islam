<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson; 
use Illuminate\Support\Facades\Auth;
use App\Models\Activity; 

class LessonController extends Controller
{
    // Function 1: Show ALL lessons
    public function index()
    {
        $lessons = Lesson::all();
        return view('lessons.index', compact('lessons'));
    }

    // Function 2: Show ONE lesson (with Questions)
    public function show($id)
    {
        // FIX APPLIED HERE:
        // We removed 'slides' from the array. 
        // Since 'slides' is a column in your database (and cast in your Model), 
        // Laravel loads it automatically. We only need to ask for 'questions'.
        $lesson = Lesson::with(['questions'])->findOrFail($id);

        return view('lessons.show', compact('lesson'));
    }

    // Function 3: Handle Lesson Completion
    public function completeLesson(Request $request, $id)
    {
        // 1. Get the current user
        $user = Auth::user();

        // 2. Fetch the lesson data
        $lesson = Lesson::findOrFail($id);
        
        // 3. Add ONLY XP (No Stars for lessons)
        $user->increment('xp', 50);
        
        // 4. Log Activity
        Activity::create([
            'user_id' => $user->id,
            'description' => "completed the lesson: " . $lesson->title, 
            'type' => 'lesson'
        ]);

        // 5. Redirect
        return redirect()->route('dashboard')->with('success', 'Lesson Completed! You earned 50 XP!');
    }
}
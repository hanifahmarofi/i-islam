<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Activity;
use App\Models\LiveQuiz;
use App\Models\QuizResult;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\LiveQuizQuestion;

class TeacherController extends Controller
{
    public function dashboard()
    {
        // 1. Get List of Students
        $students = User::where('role', 'student')->get();

        // 2. Get Live Feed (Latest Activities)
        $activities = Activity::with('user')->latest()->take(10)->get();

        // 3. Get Teacher's Quizzes
        $quizzes = LiveQuiz::where('teacher_id', Auth::id())->latest()->get();

        return view('teacher.dashboard', compact('students', 'activities', 'quizzes'));
    }

    public function createQuiz(Request $request)
{
    // Create the quiz as a DRAFT (not active, no expiry yet)
    $quiz = LiveQuiz::create([
        'teacher_id' => Auth::id(),
        'title' => $request->title,
        'code' => Str::upper(Str::random(6)),
        'is_active' => false, // Important: Not active yet!
        'expires_at' => now()->addYear(), // No timer yet
    ]);

    // Redirect to the "Builder" page to add questions
    return redirect()->route('teacher.quiz.builder', $quiz->id);
}

    public function quizBuilder($id)
{
    $quiz = LiveQuiz::with('questions')->findOrFail($id);
    return view('teacher.quiz.builder', compact('quiz'));
}

public function storeQuestion(Request $request, $id)
{
    LiveQuizQuestion::create([
        'live_quiz_id' => $id,
        'question_text' => $request->question_text,
        'option_a' => $request->option_a,
        'option_b' => $request->option_b,
        'option_c' => $request->option_c,
        'option_d' => $request->option_d,
        'correct_answer' => $request->correct_answer,
    ]);

    return back()->with('success', 'Question added!');
}

public function startQuiz($id)
{
    $quiz = LiveQuiz::findOrFail($id);
    
    $quiz->update([
        'is_active' => true,
        'expires_at' => now()->addHours(3), // Timer starts NOW
    ]);

    return redirect()->route('teacher.dashboard')->with('success', 'Quiz is now LIVE!');
}

    public function stopQuiz($id)
    {
        $quiz = LiveQuiz::findOrFail($id);
        $quiz->update(['is_active' => false]);
        return back()->with('success', 'Quiz stopped.');
    }
    
    public function leaderboard($id)
    {
        $quiz = LiveQuiz::with('results.user')->findOrFail($id);
        // Sort results by score (highest first)
        $leaderboard = $quiz->results->sortByDesc('score');
        
        return view('teacher.leaderboard', compact('quiz', 'leaderboard'));
    }

// --- 1. Show the Create Lesson Page ---
    public function createLesson()
    {
        return view('teacher.create-lesson');
    }

    // --- 2. Store the New Lesson (Handle Form Submit) ---
    public function storeLesson(Request $request)
    {
        // A. Validate the incoming data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string', // The reading material
            'points' => 'required|integer',
            'slides' => 'nullable|array',
            'slides.*' => 'image|mimes:jpeg,png,jpg|max:5120', // Max 5MB per image
            'questions' => 'nullable|array', // The quiz questions
        ]);

        // B. Create the Lesson
        // NOTE: Ensure your 'lessons' table has these columns
        $lesson = \App\Models\Lesson::create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content, 
            'xp' => $request->points, // Saving 'points' input to 'xp' column
            'teacher_id' => auth()->id(), // Optional: if you track who created it
        ]);

        // C. Handle Slide Uploads
        if ($request->hasFile('slides')) {
            $slidePaths = [];
            foreach ($request->file('slides') as $file) {
                // Save to storage/app/public/slides
                $path = $file->store('slides', 'public'); 
                $slidePaths[] = '/storage/' . $path;
            }
            // Update lesson with JSON path
            $lesson->slides = json_encode($slidePaths);
            $lesson->save();
        }

        // D. Save Quiz Questions
        // This assumes you have a 'Question' model related to Lesson
        if ($request->questions) {
            foreach ($request->questions as $q) {
                $lesson->questions()->create([
                    'question_text' => $q['text'],
                    'option_a' => $q['a'],
                    'option_b' => $q['b'],
                    'option_c' => $q['c'],
                    'option_d' => $q['d'],
                    'correct_option' => $q['correct'],
                ]);
            }
        }

        return redirect()->route('teacher.dashboard')->with('success', 'Lesson Created Successfully!');
    }

}
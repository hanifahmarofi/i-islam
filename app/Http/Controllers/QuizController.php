<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use App\Models\LiveQuiz;
use App\Models\Activity; // <--- 1. ADD THIS IMPORT

class QuizController extends Controller
{
    // --- PART 1: LESSON QUIZZES (Static) ---

    // Show the Lesson Quiz
    public function show($lesson_id)
    {
        $lesson = Lesson::with('questions')->findOrFail($lesson_id);
        return view('quiz', ['lesson' => $lesson]);
    }

    // Submit the Lesson Quiz
    public function submitLesson(Request $request, $lesson_id)
    {
        // 1. Get the lesson and questions
        $lesson = Lesson::with('questions')->findOrFail($lesson_id);
        
        // 2. Get User Answers
        $userAnswers = $request->input('answers');
        
        // 3. Check Answers
        $correctCount = 0;
        $totalQuestions = $lesson->questions->count();

        foreach ($lesson->questions as $question) {
            // Compare user answer vs correct answer
            if (isset($userAnswers[$question->id]) && 
                strtolower($userAnswers[$question->id]) === strtolower($question->correct_option)) {
                $correctCount++;
            }
        }

        // 4. LOGIC: All Correct vs Not All Correct
        if ($totalQuestions > 0 && $correctCount === $totalQuestions) {
            $user = Auth::user();
            $user->increment('xp', 50); // Add XP

            // --- 2. ADD ACTIVITY LOG HERE (For Lesson Quiz) ---
            Activity::create([
                'user_id' => Auth::id(),
                'description' => 'aced the quiz for lesson: ' . $lesson->title,
                'type' => 'quiz',
            ]);
            // --------------------------------------------------

            return redirect()->route('dashboard')->with('success', 'Alhamdulillah, you get 50XP! 🌟');
        } else {
            return redirect()->route('dashboard')->with('error', "Allahu, you didn't get all correct! You got $correctCount/$totalQuestions.");
        }
    }


    // --- PART 2: LIVE QUIZZES (Real-time) ---

    // Join a Live Quiz
    public function join(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        // Find the quiz by Code
        $quiz = LiveQuiz::where('code', $request->code)->first();

        if (!$quiz) {
            return back()->with('error', 'Invalid Code! Please check again.');
        }
        if (!$quiz->is_active) {
            return back()->with('error', 'This quiz is not active yet.');
        }

        return view('student.quiz.play', compact('quiz'));
    }

    // Submit a Live Quiz
    public function submitLive(Request $request, $id)
    {
        $quiz = LiveQuiz::with('questions')->findOrFail($id);
        $score = 0;
        $total = $quiz->questions->count();
        $answers = $request->input('answers');

        // Calculate Score
        foreach ($quiz->questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            if ($userAnswer === $question->correct_answer) {
                $score++;
            }
        }

        // --- SAVE TO DATABASE ---
        // Check if they already played to prevent duplicates
        $existingResult = \App\Models\LiveQuizResult::where('live_quiz_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$existingResult) {
            \App\Models\LiveQuizResult::create([
                'live_quiz_id' => $id,
                'user_id' => Auth::id(),
                'score' => $score,
                'total_questions' => $total,
            ]);
            
            // Optional: Give XP
            Auth::user()->increment('xp', $score * 10); 

            // --- 3. ADD ACTIVITY LOG HERE (For Live Quiz) ---
            Activity::create([
                'user_id' => Auth::id(),
                'description' => 'completed the live quiz: ' . $quiz->title,
                'type' => 'quiz',
            ]);
            // ------------------------------------------------
        }
        // ----------------------------------

        return redirect()->route('dashboard')->with('success', "Quiz Submitted! You scored $score / $total");
    }
}
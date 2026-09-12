<?php

use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Models\Lesson;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ArcadeQuizController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\FeedbackController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. LANDING PAGE ---
Route::get('/', function () {
    return view('welcome');
})->name('home');


// --- 2. AUTHENTICATION ROUTES (Login, Register, Logout) ---

// Show Login Page
Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

// Handle Login Logic
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // CHECK ROLE AND REDIRECT
        if (Auth::user()->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard'); // Default for students
    }

    return back()->withErrors(['email' => 'Invalid credentials.']);
});

// Show Register Page
Route::get('/register', function () {
    return view('auth.register'); 
})->name('register');

// Handle Register Logic
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'required|string', // Ensure role is validated
    ]);

    $user = User::create([
        'full_name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role, // Save the selected role
        'matric_id' => rand(100000, 999999),
        'total_points' => 0,
        'xp' => 0,
        // Nullable fields
        'age' => null, 'favourite_food' => null, 'mother_name' => null, 'father_name' => null, 'parents_phone' => null,
    ]);

    Auth::login($user);

    // Redirect based on role
    if ($user->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    }

    return redirect()->route('dashboard');
});

// Handle Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/'); 
})->name('logout');


// --- 3. STUDENT DASHBOARD (Protected) ---
Route::get('/dashboard', function () {
    $user = Auth::user();

    // Traffic Cop: Send Teachers & Admins to their own dashboards
    if ($user->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    }
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    // Default: Show Student Dashboard
    $lessons = Lesson::all(); 
    return view('dashboard', compact('lessons')); 
})->name('dashboard')->middleware('auth'); 


// --- 4. TEACHER DASHBOARD & ROUTES (Protected) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    Route::post('/teacher/quiz/create', [TeacherController::class, 'createQuiz'])->name('teacher.quiz.create');
    Route::get('/teacher/quiz/stop/{id}', [TeacherController::class, 'stopQuiz'])->name('teacher.quiz.stop');
    Route::get('/teacher/quiz/{id}/leaderboard', [TeacherController::class, 'leaderboard'])->name('teacher.leaderboard');

    // === NEW ROUTES FOR QUIZ BUILDER ===
    Route::get('/teacher/quiz/builder/{id}', [TeacherController::class, 'quizBuilder'])->name('teacher.quiz.builder');
    Route::post('/teacher/quiz/builder/{id}/question', [TeacherController::class, 'storeQuestion'])->name('teacher.quiz.store_question');
    Route::post('/teacher/quiz/start/{id}', [TeacherController::class, 'startQuiz'])->name('teacher.quiz.start');
});


// --- 5. MAIN FEATURES & GAMES ---
Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
Route::get('/lesson/{id}', [LessonController::class, 'show'])->name('lesson.show');
Route::post('/lesson/{id}/complete', [LessonController::class, 'completeLesson'])->name('lesson.complete');

// Game Views
Route::get('/games', function () { return view('arcade'); })->name('games.index');
Route::get('/games/scramble', function () { return view('games.scramble'); })->name('games.scramble');
Route::get('/games/hangman', function () { return view('games.hangman'); })->name('games.hangman');
Route::get('/games/match', function () { return view('games.match'); })->name('games.match');
Route::get('/games/quiz', function () { return view('games.quiz'); })->name('games.quiz');

// Game Logic (AJAX)
Route::post('/games/complete-round', [GameController::class, 'completeRound'])->name('games.complete');


// --- 6. QUIZ LOGIC (UPDATED) ---

// A. Standard Lesson Quizzes
Route::get('/quiz/{id}', [QuizController::class, 'show'])->name('quiz.show');
Route::post('/quiz/{id}/submit', [QuizController::class, 'submitLesson'])->name('quiz.submit'); // Updated to submitLesson

// B. Live Quizzes (New)
Route::post('/quiz/join', [QuizController::class, 'join'])->name('student.quiz.join');
Route::post('/live-quiz/{id}/submit', [QuizController::class, 'submitLive'])->name('live.quiz.submit'); // Updated to submitLive


// --- 7. PROFILE ---
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');


// --- 8. ADMIN ROUTES ---
Route::prefix('admin')->group(function () {
    Route::get('/register-secret', function() {
        return view('auth.register', ['url' => 'admin']);
    });
    Route::post('/create-first-admin', [AdminController::class, 'registerAdmin']);
    
    // --- FIXES APPLIED HERE ---
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Changed name from 'admin.create' to 'admin.createLesson' to match your View
    Route::get('/create-lesson', [AdminController::class, 'createLesson'])->name('admin.createLesson');
    
    // Changed name from 'admin.store' to 'admin.storeLesson'
    Route::post('/store-lesson', [AdminController::class, 'storeLesson'])->name('admin.storeLesson');
});


// --- 9. PASSWORD RESET ROUTES ---
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email|exists:users,email']);
    $token = Str::random(64);
    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        ['token' => $token, 'created_at' => now()]
    );
    try {
        Mail::to($request->email)->send(new ResetPasswordMail($token, $request->email));
        return back()->with('status', 'We have emailed your password reset link!');
    } catch (\Exception $e) {
        return back()->withErrors(['email' => 'Failed to send email. Check your mail settings.']);
    }
})->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => 'required|string|min:8|confirmed',
        'token' => 'required'
    ]);
    $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->where('token', $request->token)->first();
    if (!$resetRecord) { return back()->withErrors(['email' => 'Invalid token or email!']); }
    User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();
    return redirect()->route('login')->with('success', 'Password reset successfully! Please login.');
})->name('password.update');

Route::middleware(['auth'])->group(function () {
    // ... existing teacher routes ...

    // NEW: Create Lesson Routes
    Route::get('/teacher/lesson/create', [TeacherController::class, 'createLesson'])->name('teacher.lesson.create');
    Route::post('/teacher/lesson/store', [TeacherController::class, 'storeLesson'])->name('teacher.lesson.store');
});

Route::get('/arcade/quiz', [ArcadeQuizController::class, 'index'])->name('arcade.quiz.index');
Route::get('/arcade/quiz/generate', [ArcadeQuizController::class, 'generate'])->name('arcade.quiz.generate');

Route::get('/test-gemini', function () {
    $apiKey = env('GEMINI_API_KEY');
    
    // Ask Google: "What models are available for me?"
    $response = Illuminate\Support\Facades\Http::withoutVerifying()
        ->get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
    
    return $response->json();
});

Route::post('/feedback', [FeedbackController::class, 'store'])->middleware('auth');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // ... existing routes ...
    
    // User Actions
    Route::post('/user/{id}/block', [AdminController::class, 'toggleBlockUser'])->name('admin.user.block');
    Route::delete('/user/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.user.delete');

    // Lesson Actions
    Route::delete('/lesson/{id}/archive', [AdminController::class, 'archiveLesson'])->name('admin.lesson.archive');
    Route::delete('/lesson/{id}/force-delete', [AdminController::class, 'forceDeleteLesson'])->name('admin.lesson.forceDelete');
});
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Activity;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Fetch Students
        $students = User::where('role', 'student')->latest()->get();
        
        // 2. Fetch Teachers
        $teachers = User::where('role', 'teacher')->latest()->get();

        // 3. Fetch Lessons
        $lessons = Lesson::latest()->get();

        // 4. Fetch Activities
        $activities = Activity::with('user')->latest()->take(10)->get();

        // 5. Read Logs
        $logPath = storage_path('logs/laravel.log');
        $logs = [];
        if (File::exists($logPath)) {
            $file = file($logPath);
            $file = array_slice($file, -50);
            $logs = array_reverse($file);
        } else {
            $logs = ["No logs found."];
        }

        // 6. Send everything to the view
        return view('admin.dashboard', compact('students', 'teachers', 'lessons', 'logs', 'activities'));
    }

    public function createLesson()
    {
        return view('admin.create'); 
    }

    public function storeLesson(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'points' => 'required|integer',
            'slides.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
            'questions' => 'array', 
        ]);

        // --- 1. Handle Slide Uploads with SORTING ---
        $slidePaths = [];

        if ($request->hasFile('slides')) {
            // Get all uploaded files
            $uploadedFiles = $request->file('slides');

            // FIX: Sort them by filename naturally (1.png, 2.png, 10.png...)
            // This fixes the "5,4,3,2,1" issue on the student dashboard
            usort($uploadedFiles, function($a, $b) {
                return strnatcasecmp($a->getClientOriginalName(), $b->getClientOriginalName());
            });

            // Loop through the NOW SORTED files
            foreach ($uploadedFiles as $image) {
                // Store in 'public/slides'
                $path = $image->store('slides', 'public');
                
                // Add the path to our array (prefix with storage/ for public access)
                $slidePaths[] = 'storage/' . $path; 
            }
        }

        // --- 2. Create the Lesson ---
        // We save the $slidePaths array directly as JSON
        $lesson = Lesson::create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'points' => $request->points,
            'slides' => json_encode($slidePaths), 
        ]);

        // --- 3. Handle Questions ---
        if ($request->has('questions')) {
            foreach ($request->questions as $q) {
                Question::create([
                    'lesson_id' => $lesson->id,
                    'question_text' => $q['text'],
                    'option_a' => $q['a'],
                    'option_b' => $q['b'],
                    'option_c' => $q['c'],
                    'option_d' => $q['d'],
                    'correct_option' => $q['correct'],
                    'points' => 10 
                ]);
            }
        }

        return redirect()->route('admin.dashboard')->with('success', 'Lesson & Quiz created successfully!');
    }
    
    public function registerAdmin(Request $request) {
         $user = User::create([
            'full_name' => 'Admin',
            'email' => 'admin@i-islam.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'matric_id' => 'ADMIN001'
         ]);
         return redirect()->route('login');
    }

// --- USER MANAGEMENT ---

public function toggleBlockUser($id)
{
    $user = \App\Models\User::findOrFail($id);
    
    // Toggle the status (if true, make false. if false, make true)
    $user->is_blocked = !$user->is_blocked;
    $user->save();

    $status = $user->is_blocked ? 'BLOCKED 🚫' : 'UNBLOCKED ✅';
    return back()->with('success', "User has been $status.");
}

public function deleteUser($id)
{
    $user = \App\Models\User::findOrFail($id);
    $user->delete(); // Permanently deletes user
    return back()->with('success', 'User permanently deleted.');
}

// --- LESSON MANAGEMENT ---

public function archiveLesson($id)
{
    $lesson = \App\Models\Lesson::findOrFail($id);
    $lesson->delete(); // Soft Delete (Archive)
    return back()->with('success', 'Lesson archived successfully.');
}

public function forceDeleteLesson($id)
{
    // We use withTrashed() to find it even if it's already archived
    $lesson = \App\Models\Lesson::withTrashed()->findOrFail($id);
    $lesson->forceDelete(); // Permanent Delete
    return back()->with('success', 'Lesson PERMANENTLY deleted.');
}

}
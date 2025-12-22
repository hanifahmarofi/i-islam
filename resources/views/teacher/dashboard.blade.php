<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-900 text-white font-sans">

    <nav class="bg-gray-800 p-4 border-b border-gray-700 flex justify-between">
        <h1 class="text-xl font-bold text-green-400">👩‍🏫 Teacher Panel</h1>
        <form action="{{ route('logout') }}" method="POST"><button>@csrf Logout</button></form>
    </nav>

    <div class="max-w-7xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
                <h2 class="text-lg font-bold mb-4 text-blue-400"><i class="fa-solid fa-bolt"></i> Live Student Activity</h2>
                <div class="space-y-3">
                    @foreach($activities as $log)
                    <div class="flex items-center gap-3 p-3 bg-gray-700/50 rounded-lg">
                        <div class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-300 flex items-center justify-center font-bold">
                            {{ substr($log->user->full_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm">
                                <span class="font-bold text-white">{{ $log->user->full_name }}</span> 
                                <span class="text-gray-400">{{ $log->description }}</span>
                            </p>
                            <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
                <h2 class="text-lg font-bold mb-4 text-green-400">Students List</h2>
                <table class="w-full text-left text-sm">
                    <thead><tr class="text-gray-400"><th>Name</th><th>Email</th><th>Total XP</th></tr></thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr class="border-b border-gray-700">
                            <td class="py-2">{{ $student->full_name }}</td>
                            <td class="py-2 text-gray-400">{{ $student->email }}</td>
                            <td class="py-2 text-yellow-400 font-bold">{{ $student->total_points }} XP</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">

            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-2xl">📝</span>
                    <h3 class="text-xl font-bold text-white">Lesson Manager</h3>
                </div>
                <p class="text-gray-400 text-sm mb-4">Upload slides, add reading material, and build lesson quizzes.</p>
                
                <a href="{{ route('teacher.lesson.create') }}" class="block w-full bg-green-600 hover:bg-green-500 text-white text-center font-bold py-3 rounded-lg transition transform hover:scale-[1.02]">
                    + Create New Lesson
                </a>
            </div>

            <div class="bg-gray-800 p-6 rounded-xl border border-purple-500/30">
                <h2 class="text-lg font-bold mb-4 text-purple-400">🚀 Conduct Live Quiz</h2>
                
                <form action="{{ route('teacher.quiz.create') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="text" name="title" placeholder="Quiz Title (e.g., Friday Test)" class="w-full p-3 bg-gray-900 rounded border border-gray-600 text-white" required>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 py-3 rounded font-bold transition">Start 3-Hour Quiz</button>
                </form>
            </div>

            @foreach($quizzes as $quiz)
            <div class="bg-gray-800 p-4 rounded-xl border {{ $quiz->is_active ? 'border-green-500' : 'border-gray-600' }}">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold">{{ $quiz->title }}</h3>
                    <span class="text-xs font-mono bg-gray-900 px-2 py-1 rounded">CODE: {{ $quiz->code }}</span>
                </div>
                
                @if($quiz->is_active && $quiz->expires_at > now())
                    <p class="text-xs text-green-400 mb-2">● Active (Expires: {{ $quiz->expires_at->diffForHumans() }})</p>
                    <div class="flex gap-2">
                        <a href="{{ route('teacher.leaderboard', $quiz->id) }}" class="flex-1 bg-blue-600 text-center py-2 rounded text-xs font-bold">Leaderboard</a>
                        <a href="{{ route('teacher.quiz.stop', $quiz->id) }}" class="flex-1 bg-red-600 text-center py-2 rounded text-xs font-bold">Stop</a>
                    </div>
                @else
                    <p class="text-xs text-red-400 mb-2">● Expired / Ended</p>
                    <a href="{{ route('teacher.leaderboard', $quiz->id) }}" class="block w-full bg-gray-700 text-center py-2 rounded text-xs font-bold">View Final Results</a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>
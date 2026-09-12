<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Panel - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f392b; /* Match Student Background */
            color: white;
            overflow-x: hidden;
        }

        /* --- PARTICLES --- */
        .particle {
            position: fixed;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            animation: floatUp linear infinite;
        }
        @keyframes floatUp {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }

        /* --- GLASS EFFECT --- */
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
        }
        
        .navbar {
            background: rgba(6, 78, 59, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* --- SCROLLBAR --- */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #064e3b; }
        ::-webkit-scrollbar-thumb { background: #34d399; border-radius: 4px; }
    </style>
</head>
<body class="relative min-h-screen pb-20">

    <div id="particles"></div>

    <nav class="navbar fixed w-full top-0 z-40 px-6 py-4 flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-3">
            <span class="text-yellow-400 text-2xl"><i class="fa-solid fa-chalkboard-user"></i></span>
            <span class="text-xl font-bold tracking-wide text-green-100">Teacher Panel</span>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="bg-red-500/80 hover:bg-red-600 text-white px-5 py-2 rounded-lg text-sm font-bold transition shadow-lg transform hover:scale-105">
                Logout
            </button>
        </form>
    </nav>

    <div class="max-w-7xl mx-auto mt-24 px-6 relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            
            <div class="glass-panel p-6 border-l-4 border-l-blue-400 shadow-xl">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-blue-200">
                    <i class="fa-solid fa-bolt text-yellow-400 animate-pulse"></i> Live Student Activity
                </h2>
                <div class="space-y-4 max-h-96 overflow-y-auto pr-2 custom-scroll">
                    @foreach($activities as $log)
                    <div class="flex items-center gap-4 p-4 bg-white/5 rounded-xl hover:bg-white/10 transition border border-white/5">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 text-white flex items-center justify-center font-bold shadow-lg">
                            {{ substr($log->user->full_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm text-gray-200">
                                <span class="font-bold text-white">{{ $log->user->full_name }}</span> 
                                {{ $log->description }}
                            </p>
                            <p class="text-xs text-blue-300/80 mt-1">
                                <i class="far fa-clock mr-1"></i> {{ $log->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="glass-panel p-6 border-l-4 border-l-green-400 shadow-xl">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-green-200">
                    <i class="fa-solid fa-users"></i> Enrolled Students
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="text-green-300 border-b border-white/10">
                                <th class="pb-3 pl-2">Name</th>
                                <th class="pb-3">Email</th>
                                <th class="pb-3">Total XP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($students as $student)
                            <tr class="hover:bg-white/5 transition">
                                <td class="py-3 pl-2 font-semibold">{{ $student->full_name }}</td>
                                <td class="py-3 text-gray-400">{{ $student->email }}</td>
                                <td class="py-3">
                                    <span class="bg-yellow-500/20 text-yellow-300 px-3 py-1 rounded-full font-bold text-xs border border-yellow-500/30">
                                        {{ $student->total_points }} XP
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-8">

            <div class="bg-gradient-to-br from-emerald-600 to-teal-800 p-6 rounded-2xl shadow-xl border border-emerald-400/30 relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition"></div>
                
                <div class="flex items-center gap-3 mb-2 relative z-10">
                    <span class="text-3xl bg-white/20 w-12 h-12 flex items-center justify-center rounded-lg">📝</span>
                    <h3 class="text-xl font-bold text-white">Lesson Manager</h3>
                </div>
                <p class="text-emerald-100 text-sm mb-6 relative z-10">Upload slides & build content.</p>
                
                <a href="{{ route('teacher.lesson.create') }}" class="block w-full bg-white text-emerald-800 hover:bg-emerald-50 text-center font-bold py-3 rounded-xl shadow-lg transition transform hover:translate-y-[-2px] relative z-10">
                    + Create New Lesson
                </a>
            </div>

            <div class="bg-gradient-to-br from-purple-600 to-indigo-800 p-6 rounded-2xl shadow-xl border border-purple-400/30">
                <h2 class="text-lg font-bold mb-4 text-white flex items-center gap-2">
                    <i class="fa-solid fa-rocket"></i> Conduct Live Quiz
                </h2>
                
                <form action="{{ route('teacher.quiz.create') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="title" placeholder="Quiz Title (e.g., Friday Test)" 
                        class="w-full p-3 bg-white/10 rounded-xl border border-white/20 text-white placeholder-purple-200 focus:outline-none focus:ring-2 focus:ring-purple-400" required>
                    
                    <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-purple-900 py-3 rounded-xl font-bold transition shadow-lg transform hover:scale-[1.02]">
                        Start 3-Hour Quiz
                    </button>
                </form>
            </div>

            <div class="space-y-4">
                @foreach($quizzes as $quiz)
                <div class="glass-panel p-4 hover:bg-white/5 transition border-l-4 {{ $quiz->is_active ? 'border-l-green-400' : 'border-l-gray-500' }}">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg">{{ $quiz->title }}</h3>
                        <span class="text-xs font-mono bg-white/10 px-2 py-1 rounded text-purple-200 border border-purple-500/30">
                            {{ $quiz->code }}
                        </span>
                    </div>
                    
                    @if($quiz->is_active && $quiz->expires_at > now())
                        <p class="text-xs text-green-300 mb-3 flex items-center gap-1">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> 
                            Active (Ends {{ $quiz->expires_at->diffForHumans() }})
                        </p>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('teacher.leaderboard', $quiz->id) }}" class="bg-blue-600/80 hover:bg-blue-600 text-center py-2 rounded-lg text-xs font-bold transition">Leaderboard</a>
                            <a href="{{ route('teacher.quiz.stop', $quiz->id) }}" class="bg-red-500/80 hover:bg-red-500 text-center py-2 rounded-lg text-xs font-bold transition">Stop Quiz</a>
                        </div>
                    @else
                        <p class="text-xs text-gray-400 mb-3">● Expired / Ended</p>
                        <a href="{{ route('teacher.leaderboard', $quiz->id) }}" class="block w-full bg-gray-700/50 hover:bg-gray-700 text-center py-2 rounded-lg text-xs font-bold transition border border-gray-600">
                            View Final Results
                        </a>
                    @endif
                </div>
                @endforeach
            </div>

        </div>
    </div>

    <script>
        const particleContainer = document.getElementById('particles');
        for(let i=0; i<35; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + 'vw';
            const size = Math.random() * 6 + 3; 
            p.style.width = size + 'px';
            p.style.height = size + 'px';
            p.style.animationDuration = Math.random() * 8 + 4 + 's';
            p.style.animationDelay = Math.random() * 5 + 's';
            particleContainer.appendChild(p);
        }
    </script>
</body>
</html>
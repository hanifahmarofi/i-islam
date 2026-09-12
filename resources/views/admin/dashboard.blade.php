<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Fira+Code:wght@400&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f392b; /* Signature Dark Green */
            color: white;
            overflow-x: hidden;
        }

        /* --- RAINING STARS EFFECT --- */
        .star {
            position: fixed;
            background: white;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            opacity: 0;
            animation: fall linear infinite;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
        }

        @keyframes fall {
            0% { transform: translateY(-10vh) translateX(0); opacity: 1; }
            80% { opacity: 0.8; }
            100% { transform: translateY(110vh) translateX(20px); opacity: 0; }
        }

        /* --- GLASS EFFECT --- */
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            background: rgba(6, 78, 59, 0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* --- SCROLLBAR --- */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #064e3b; }
        ::-webkit-scrollbar-thumb { background: #34d399; border-radius: 4px; }
    </style>
</head>
<body class="relative min-h-screen pb-10">

    <div id="star-container"></div>

    <nav class="navbar fixed w-full top-0 z-50 px-6 py-4 flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-3">
            <span class="text-3xl text-yellow-400"><i class="fa-solid fa-shield-halved"></i></span>
            <h1 class="text-2xl font-bold tracking-wide text-green-100">Admin Panel</h1>
        </div>
        
        <div class="flex items-center gap-6">
            <span class="text-green-200 text-sm hidden md:block">
                Welcome, <span class="font-bold text-white">{{ Auth::user()->full_name ?? 'Admin' }}</span>
            </span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="bg-red-500/80 hover:bg-red-600 text-white px-5 py-2 rounded-xl text-sm font-bold transition shadow-lg transform hover:scale-105 border border-red-400/30">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    @if(session('success'))
    <div class="max-w-7xl mx-auto mt-24 px-6 relative z-10 animate-bounce">
        <div class="glass-panel border-l-4 border-green-400 p-4 flex items-center gap-4 bg-green-900/40">
            <div class="bg-green-500 rounded-full w-8 h-8 flex items-center justify-center text-white">
                <i class="fa-solid fa-check"></i>
            </div>
            <span class="text-green-100 font-semibold">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="max-w-[90rem] mx-auto mt-24 px-6 grid grid-cols-1 lg:grid-cols-4 gap-8 relative z-10">

        <div class="lg:col-span-3 space-y-8">

            <div class="glass-panel p-8 flex flex-col md:flex-row justify-between items-center gap-4 bg-gradient-to-r from-emerald-900/60 to-teal-900/60">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-1">Dashboard Overview</h2>
                    <p class="text-emerald-200 text-sm">Manage your system, users, and content from here.</p>
                </div>
                <a href="{{ route('admin.createLesson') }}" 
                   class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:scale-105 flex items-center gap-3 border border-white/20">
                    <i class="fa-solid fa-plus-circle text-xl"></i> Create New Lesson
                </a>
            </div>

            <div class="glass-panel p-6 border-t-4 border-purple-400">
                <h2 class="text-xl font-bold mb-6 text-purple-200 flex items-center gap-2">
                    <i class="fa-solid fa-book-open"></i> Manage Lessons
                </h2>
                <div class="overflow-x-auto rounded-xl border border-white/10">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-white/10 text-gray-200 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="p-4">Title</th>
                                <th class="p-4">Description</th>
                                <th class="p-4">Points</th>
                                <th class="p-4">Created</th>
                                <th class="p-4 text-right">Actions</th> </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($lessons as $lesson)
                            <tr class="hover:bg-white/5 transition duration-150">
                                <td class="p-4 font-bold text-white">{{ $lesson->title }}</td>
                                <td class="p-4 text-gray-300">{{ Str::limit($lesson->description, 30) }}</td>
                                <td class="p-4">
                                    <span class="bg-yellow-500/20 text-yellow-300 px-3 py-1 rounded-full font-bold text-xs border border-yellow-500/30">
                                        {{ $lesson->points }} XP
                                    </span>
                                </td>
                                <td class="p-4 text-gray-400">{{ $lesson->created_at->format('d M Y') }}</td>
                                
                                <td class="p-4 flex justify-end gap-2">
                                    <form action="{{ route('admin.lesson.archive', $lesson->id) }}" method="POST" onsubmit="return confirm('Archive this lesson?');">
                                        @csrf @method('DELETE')
                                        <button class="bg-orange-500/20 text-orange-400 p-2 rounded hover:bg-orange-500 hover:text-white transition" title="Archive">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.lesson.forceDelete', $lesson->id) }}" method="POST" onsubmit="return confirm('WARNING: This will permanently delete this lesson!');">
                                        @csrf @method('DELETE')
                                        <button class="bg-red-500/20 text-red-400 p-2 rounded hover:bg-red-500 hover:text-white transition" title="Delete Forever">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400 italic">No lessons created yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="glass-panel p-6 border-t-4 border-blue-400">
                    <h2 class="text-lg font-bold mb-4 text-blue-200 flex items-center gap-2">
                        <i class="fa-solid fa-user-graduate"></i> Students
                    </h2>
                    <div class="overflow-y-auto max-h-60 pr-2 custom-scroll">
                        <ul class="space-y-2">
                            @forelse($students as $student)
                            <li class="p-3 bg-white/5 rounded-lg flex justify-between items-center hover:bg-white/10 transition border border-white/5">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-bold text-white">{{ $student->full_name }}</p>
                                        @if($student->is_blocked)
                                            <span class="bg-red-600 text-white text-[10px] px-2 py-0.5 rounded uppercase font-bold">Blocked</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400">{{ $student->email }}</p>
                                    <span class="text-xs bg-blue-500/20 text-blue-300 px-2 py-0.5 rounded mt-1 inline-block">
                                        {{ $student->xp ?? 0 }} XP
                                    </span>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('admin.user.block', $student->id) }}" method="POST">
                                        @csrf
                                        <button class="text-xs px-2 py-1 rounded font-bold transition {{ $student->is_blocked ? 'bg-green-500/20 text-green-400 hover:bg-green-500 hover:text-white' : 'bg-yellow-500/20 text-yellow-400 hover:bg-yellow-500 hover:text-white' }}">
                                            {{ $student->is_blocked ? 'Unblock' : 'Block' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.user.delete', $student->id) }}" method="POST" onsubmit="return confirm('Delete this user permanently?');">
                                        @csrf @method('DELETE')
                                        <button class="text-xs bg-red-500/20 text-red-400 px-2 py-1 rounded font-bold hover:bg-red-500 hover:text-white transition">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                            @empty
                            <li class="text-gray-400 text-sm italic text-center py-4">No students found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="glass-panel p-6 border-t-4 border-yellow-400">
                    <h2 class="text-lg font-bold mb-4 text-yellow-200 flex items-center gap-2">
                        <i class="fa-solid fa-chalkboard-user"></i> Teachers
                    </h2>
                    <div class="overflow-y-auto max-h-60 pr-2 custom-scroll">
                        <ul class="space-y-2">
                            @forelse($teachers as $teacher)
                            <li class="p-3 bg-white/5 rounded-lg flex justify-between items-center hover:bg-white/10 transition border border-white/5">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-bold text-white">{{ $teacher->full_name }}</p>
                                        @if($teacher->is_blocked)
                                            <span class="bg-red-600 text-white text-[10px] px-2 py-0.5 rounded uppercase font-bold">Blocked</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400">{{ $teacher->email }}</p>
                                    <span class="text-xs bg-yellow-500/20 text-yellow-300 px-2 py-0.5 rounded mt-1 inline-block">Teacher</span>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('admin.user.block', $teacher->id) }}" method="POST">
                                        @csrf
                                        <button class="text-xs px-2 py-1 rounded font-bold transition {{ $teacher->is_blocked ? 'bg-green-500/20 text-green-400 hover:bg-green-500 hover:text-white' : 'bg-yellow-500/20 text-yellow-400 hover:bg-yellow-500 hover:text-white' }}">
                                            {{ $teacher->is_blocked ? 'Unblock' : 'Block' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.user.delete', $teacher->id) }}" method="POST" onsubmit="return confirm('Delete this teacher permanently?');">
                                        @csrf @method('DELETE')
                                        <button class="text-xs bg-red-500/20 text-red-400 px-2 py-1 rounded font-bold hover:bg-red-500 hover:text-white transition">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                            @empty
                            <li class="text-gray-400 text-sm italic text-center py-4">No teachers found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="glass-panel p-0 overflow-hidden flex flex-col h-[85vh] border border-green-500/30 shadow-2xl">
                <div class="bg-black/40 p-3 flex items-center gap-2 border-b border-white/10">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="ml-2 text-xs text-gray-400 font-mono">system_logs.exe</span>
                </div>

                <div class="p-4 overflow-y-auto flex-1 font-mono text-xs space-y-3 bg-black/20 text-green-400">
                    <div class="text-gray-500 mb-4">
                        // i-Islam System Monitor<br>
                        // v2.0.1 - Connected<br>
                        // ------------------------
                    </div>
                    
                    @forelse($logs as $log)
                        <div class="break-words hover:bg-white/5 p-1 rounded transition">
                            <span class="text-green-600 mr-1">➜</span> 
                            <span class="opacity-90">{{ $log }}</span>
                        </div>
                    @empty
                        <div class="text-gray-500 italic">No logs available. System quiet.</div>
                    @endforelse
                    
                    <div class="animate-pulse">_</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const starContainer = document.getElementById('star-container');
        
        function createStar() {
            const star = document.createElement('div');
            star.classList.add('star');
            
            // Randomize size, position, and speed
            const size = Math.random() * 3 + 2 + 'px'; // 2px to 5px
            star.style.width = size;
            star.style.height = size;
            
            star.style.left = Math.random() * 100 + 'vw';
            star.style.animationDuration = Math.random() * 3 + 2 + 's'; // 2s to 5s fall time
            
            starContainer.appendChild(star);
            
            // Remove star after animation to prevent memory leak
            setTimeout(() => {
                star.remove();
            }, 5000);
        }

        // Create a new star every 100ms
        setInterval(createStar, 100);
    </script>

</body>
</html>
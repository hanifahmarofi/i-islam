<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-900 text-white font-sans">

    <nav class="bg-gray-800 p-4 border-b border-gray-700 flex justify-between items-center sticky top-0 z-50">
        <h1 class="text-xl font-bold text-green-400">🛡️ Admin Panel</h1>
        <div class="flex items-center gap-4">
            <span class="text-gray-400">Welcome, {{ Auth::user()->full_name ?? 'Admin' }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded text-sm font-bold transition">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    @if(session('success'))
    <div class="max-w-7xl mx-auto mt-6 px-6">
        <div class="bg-green-600/20 border border-green-500 text-green-400 p-4 rounded-lg flex items-center gap-3">
            <i class="fa-solid fa-check-circle text-xl"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="max-w-7xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-4 gap-6">

        <div class="lg:col-span-3 space-y-6">

            <div class="flex justify-between items-center bg-gray-800 p-4 rounded-xl border border-gray-700">
                <h2 class="text-lg font-bold text-white">Dashboard Overview</h2>
                <a href="{{ route('admin.createLesson') }}" 
                   class="bg-green-600 hover:bg-green-500 text-white font-bold py-2 px-4 rounded shadow-lg transition flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Create New Lesson
                </a>
            </div>

            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
                <h2 class="text-lg font-bold mb-4 text-purple-400"><i class="fa-solid fa-book-open"></i> Manage Lessons</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-900 text-gray-400 uppercase text-xs">
                            <tr>
                                <th class="p-3">Title</th>
                                <th class="p-3">Description</th>
                                <th class="p-3">Points</th>
                                <th class="p-3">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($lessons as $lesson)
                            <tr class="hover:bg-gray-700/50 transition">
                                <td class="p-3 font-bold text-white">{{ $lesson->title }}</td>
                                <td class="p-3 text-gray-400">{{ Str::limit($lesson->description, 50) }}</td>
                                <td class="p-3 text-yellow-400 font-bold">{{ $lesson->points }} XP</td>
                                <td class="p-3 text-gray-500">{{ $lesson->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500 italic">No lessons created yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
                    <h2 class="text-lg font-bold mb-4 text-blue-400"><i class="fa-solid fa-user-graduate"></i> Students</h2>
                    <div class="overflow-y-auto max-h-60">
                        <ul class="divide-y divide-gray-700">
                            @forelse($students as $student)
                            <li class="py-2 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-bold text-white">{{ $student->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $student->email }}</p>
                                </div>
                                <span class="text-xs bg-blue-900 text-blue-300 px-2 py-1 rounded">{{ $student->xp ?? 0 }} XP</span>
                            </li>
                            @empty
                            <li class="text-gray-500 text-sm italic text-center py-4">No students found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
                    <h2 class="text-lg font-bold mb-4 text-yellow-400"><i class="fa-solid fa-chalkboard-user"></i> Teachers</h2>
                    <div class="overflow-y-auto max-h-60">
                        <ul class="divide-y divide-gray-700">
                            @forelse($teachers as $teacher)
                            <li class="py-2 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-bold text-white">{{ $teacher->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $teacher->email }}</p>
                                </div>
                                <span class="text-xs bg-yellow-900 text-yellow-300 px-2 py-1 rounded">Teacher</span>
                            </li>
                            @empty
                            <li class="text-gray-500 text-sm italic text-center py-4">No teachers found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <div class="space-y-6">
            <div class="bg-black p-4 rounded-xl border border-gray-700 shadow-lg h-full max-h-[80vh] overflow-hidden flex flex-col">
                <h2 class="text-lg font-bold mb-4 text-green-500 font-mono"><i class="fa-solid fa-terminal"></i> System Logs</h2>
                <div class="overflow-y-auto flex-1 font-mono text-xs space-y-2 pr-2 text-green-400">
                    @forelse($logs as $log)
                        <div class="border-b border-gray-800 pb-1 break-words">
                            <span class="text-gray-500">>></span> {{ $log }}
                        </div>
                    @empty
                        <div class="text-gray-500 italic">No logs available.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</body>
</html>
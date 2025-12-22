<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Lessons - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0f392b] min-h-screen p-10 font-sans text-white">

    <div class="max-w-4xl mx-auto flex justify-between items-center mb-10">
        <h1 class="text-3xl font-bold text-green-100">📚 All Available Lessons</h1>
        <a href="{{ route('dashboard') }}" class="bg-green-700 hover:bg-green-600 px-4 py-2 rounded-lg text-sm font-bold transition">
            ← Back to Dashboard
        </a>
    </div>

    <div class="max-w-4xl mx-auto grid gap-6">
        
        @foreach($lessons as $lesson)
        <div class="bg-white/10 backdrop-blur-md border border-white/10 p-6 rounded-2xl flex justify-between items-center hover:bg-white/20 transition group">
            <div>
                <h2 class="text-xl font-bold text-green-300 group-hover:text-green-100">{{ $lesson->title }}</h2>
                <p class="text-gray-400 text-sm mt-1">{{Str::limit($lesson->description, 80)}}</p>
            </div>

            <div class="text-right">
                <span class="bg-yellow-500/20 text-yellow-300 text-xs font-bold px-3 py-1 rounded-full border border-yellow-500/30">
                    +{{ $lesson->points }} XP
                </span>
                <br>
                <a href="{{ route('lesson.show', $lesson->id) }}" class="inline-block mt-3 text-green-400 font-bold hover:text-white transition">
                    Read Now →
                </a>
            </div>
        </div>
        @endforeach

        @if($lessons->isEmpty())
            <div class="text-center text-gray-400 py-10">
                No lessons added yet. Check back soon!
            </div>
        @endif

    </div>

</body>
</html>
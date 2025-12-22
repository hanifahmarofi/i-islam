<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Center - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-50 min-h-screen p-6">

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('dashboard') }}" class="text-green-700 font-bold hover:underline">
                ← Back to Dashboard
            </a>
            <h1 class="text-3xl font-bold text-gray-800">📖 Learning Center</h1>
            <div class="w-20"></div> </div>

        <div class="grid grid-cols-1 gap-6">
            @foreach($lessons as $lesson)
            <div class="bg-white p-6 rounded-xl shadow-md border-l-8 border-green-500 hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-green-800">{{ $lesson->title }}</h2>
                        <p class="text-gray-600 mt-2 text-lg">{{ $lesson->description }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-sm font-bold px-3 py-1 rounded-full mb-3">
                            +{{ $lesson->points }} Points
                        </span>
                        <br>
                        <a href="{{ route('lesson.show', $lesson->id) }}" class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-700 transition inline-block">
                            Start Lesson
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</body>
</html>
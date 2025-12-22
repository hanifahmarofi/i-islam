<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lesson->title }} - i-Islam</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-50 min-h-screen p-10">

    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border-t-8 border-green-500">
        
        <!-- Header -->
        <div class="bg-green-100 p-8 border-b border-green-200">
            <a href="{{ route('learning.index') }}" class="text-sm text-green-700 font-bold hover:underline mb-4 inline-block">← Back</a>
            <h1 class="text-4xl font-bold text-green-800">{{ $lesson->title }}</h1>
            <p class="text-green-600 mt-2 text-lg">{{ $lesson->description }}</p>
        </div>

        <!-- SLIDESHOW (New Feature) -->
        @if($lesson->slides->count() > 0)
        <div class="p-8 bg-black/90">
            <div class="flex overflow-x-auto gap-4 snap-x py-4">
                @foreach($lesson->slides as $slide)
                <img src="{{ asset('storage/' . $slide->image_path) }}" class="h-64 rounded-lg shadow-lg snap-center border-2 border-white/20">
                @endforeach
            </div>
            <p class="text-center text-gray-400 text-xs mt-2">Scroll horizontally to see slides</p>
        </div>
        @endif

        <!-- Content -->
        <div class="p-10 text-gray-800 leading-relaxed text-lg">
            <p>{{ $lesson->content }}</p>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 p-6 text-center border-t border-gray-100">
            <a href="{{ route('quiz.show', $lesson->id) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg shadow inline-block">
                Take Quiz 📝
            </a>
        </div>

    </div>
</body>
</html>
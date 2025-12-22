<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playing: {{ $quiz->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white font-sans antialiased min-h-screen p-6">

    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-8 border-b border-gray-700 pb-4">
            <div>
                <h1 class="text-2xl font-bold text-purple-400">{{ $quiz->title }}</h1>
                <p class="text-gray-400 text-sm">Timer: <span class="text-yellow-400 font-mono">{{ $quiz->expires_at->diffForHumans() }}</span></p>
            </div>
            <div class="bg-gray-800 px-4 py-2 rounded text-sm">
                Questions: <span class="font-bold text-white">{{ $quiz->questions->count() }}</span>
            </div>
        </div>

        <form action="{{ route('live.quiz.submit', $quiz->id) }}" method="POST">
            @csrf
            
            @foreach($quiz->questions as $index => $q)
                <div class="bg-gray-800 rounded-lg p-6 mb-6 shadow-md border border-gray-700">
                    <h3 class="text-lg font-bold mb-4">
                        <span class="text-purple-500 mr-2">{{ $index + 1 }}.</span> {{ $q->question_text }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <label class="cursor-pointer block bg-gray-700 hover:bg-gray-600 p-3 rounded border border-gray-600 transition">
                            <input type="radio" name="answers[{{ $q->id }}]" value="option_a" class="mr-2 accent-purple-500" required>
                            {{ $q->option_a }}
                        </label>

                        <label class="cursor-pointer block bg-gray-700 hover:bg-gray-600 p-3 rounded border border-gray-600 transition">
                            <input type="radio" name="answers[{{ $q->id }}]" value="option_b" class="mr-2 accent-purple-500">
                            {{ $q->option_b }}
                        </label>

                        <label class="cursor-pointer block bg-gray-700 hover:bg-gray-600 p-3 rounded border border-gray-600 transition">
                            <input type="radio" name="answers[{{ $q->id }}]" value="option_c" class="mr-2 accent-purple-500">
                            {{ $q->option_c }}
                        </label>

                        <label class="cursor-pointer block bg-gray-700 hover:bg-gray-600 p-3 rounded border border-gray-600 transition">
                            <input type="radio" name="answers[{{ $q->id }}]" value="option_d" class="mr-2 accent-purple-500">
                            {{ $q->option_d }}
                        </label>
                    </div>
                </div>
            @endforeach

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-lg text-xl shadow-lg transition transform hover:scale-105">
                Submit Answers 📝
            </button>
        </form>
    </div>

</body>
</html>
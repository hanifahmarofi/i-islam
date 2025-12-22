<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz: {{ $lesson->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-10 px-4">

    <div class="max-w-3xl mx-auto">
        <div class="bg-white p-6 rounded-t-xl shadow border-b border-gray-200">
            <h1 class="text-2xl font-bold text-green-700">Quiz: {{ $lesson->title }}</h1>
            <p class="text-gray-500">Answer the questions below to earn points!</p>
        </div>

        <form action="/quiz/{{ $lesson->id }}/submit" method="POST">
    
             @csrf
            
            <div class="bg-white p-6 rounded-b-xl shadow space-y-8">
                
                @foreach($lesson->questions as $index => $question)
                <div class="border-b border-gray-100 pb-6 last:border-0">
                    <p class="font-bold text-lg text-gray-800 mb-3">
                        {{ $index + 1 }}. {{ $question->question_text }}
                    </p>

                    <div class="space-y-2">
                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="radio" name="answers[{{ $question->id }}]" value="a" class="h-5 w-5 text-green-600">
                            <span class="text-gray-700">{{ $question->option_a }}</span>
                        </label>

                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="radio" name="answers[{{ $question->id }}]" value="b" class="h-5 w-5 text-green-600">
                            <span class="text-gray-700">{{ $question->option_b }}</span>
                        </label>

                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="radio" name="answers[{{ $question->id }}]" value="c" class="h-5 w-5 text-green-600">
                            <span class="text-gray-700">{{ $question->option_c }}</span>
                        </label>

                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="radio" name="answers[{{ $question->id }}]" value="d" class="h-5 w-5 text-green-600">
                            <span class="text-gray-700">{{ $question->option_d }}</span>
                        </label>
                    </div>
                </div>
                @endforeach

            </div>

            <div class="mt-6 text-center">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-10 rounded-full shadow-lg transition transform hover:scale-105">
                    Submit Answers 📝
                </button>
            </div>
        </form>
    </div>

</body>
</html>
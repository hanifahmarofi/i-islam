@extends('layouts.app') {{-- Make sure this matches your layout file name --}}

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Quiz Builder: {{ $quiz->title }}</h1>
            <p class="text-gray-400 text-sm">Draft Mode (Code: <span class="text-yellow-400">{{ $quiz->code }}</span>)</p>
        </div>
        
        {{-- PUBLISH BUTTON: Only clickable if there is at least 1 question --}}
        @if($quiz->questions->count() > 0)
            <form action="{{ route('teacher.quiz.start', $quiz->id) }}" method="POST">
                @csrf
                <button type="submit" 
                    class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition transform hover:scale-105">
                    🚀 Publish & Start Timer
                </button>
            </form>
        @else
            <button disabled class="bg-gray-600 text-gray-400 font-bold py-2 px-6 rounded-lg cursor-not-allowed opacity-50">
                Add questions first
            </button>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- LEFT COLUMN: ADD NEW QUESTION FORM --}}
        <div class="lg:col-span-1">
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 shadow-md sticky top-6">
                <h2 class="text-xl font-bold text-white mb-4 border-b border-gray-700 pb-2">Add New Question</h2>

                <form action="{{ route('teacher.quiz.store_question', $quiz->id) }}" method="POST">
                    @csrf
                    
                    {{-- Question Text --}}
                    <div class="mb-4">
                        <label class="block text-gray-300 text-sm font-bold mb-2">Question</label>
                        <input type="text" name="question_text" required
                            class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-white focus:outline-none focus:border-purple-500"
                            placeholder="e.g., What is 2 + 2?">
                    </div>

                    {{-- Options A-D --}}
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div>
                            <label class="block text-gray-400 text-xs mb-1">Option A</label>
                            <input type="text" name="option_a" required class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-sm text-white">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs mb-1">Option B</label>
                            <input type="text" name="option_b" required class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-sm text-white">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs mb-1">Option C</label>
                            <input type="text" name="option_c" required class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-sm text-white">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs mb-1">Option D</label>
                            <input type="text" name="option_d" required class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-sm text-white">
                        </div>
                    </div>

                    {{-- Correct Answer --}}
                    <div class="mb-4">
                        <label class="block text-gray-300 text-sm font-bold mb-2">Correct Answer</label>
                        <select name="correct_answer" class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-white">
                            <option value="option_a">Option A</option>
                            <option value="option_b">Option B</option>
                            <option value="option_c">Option C</option>
                            <option value="option_d">Option D</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                        + Add Question
                    </button>
                </form>
            </div>
        </div>

        {{-- RIGHT COLUMN: QUESTIONS LIST --}}
        <div class="lg:col-span-2">
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 shadow-md">
                <h2 class="text-xl font-bold text-white mb-4">Questions Added ({{ $quiz->questions->count() }})</h2>

                @if($quiz->questions->isEmpty())
                    <div class="text-center py-10 text-gray-500">
                        <p>No questions yet. Use the form on the left to add one.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($quiz->questions as $index => $q)
                            <div class="bg-gray-700 p-4 rounded-lg border-l-4 border-purple-500">
                                <div class="flex justify-between">
                                    <h3 class="font-bold text-white text-lg">
                                        <span class="text-purple-400">Q{{ $index + 1 }}:</span> {{ $q->question_text }}
                                    </h3>
                                    {{-- Delete Button (Optional, if you want to add later) --}}
                                    {{-- <button class="text-red-400 text-sm hover:underline">Delete</button> --}}
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mt-2 text-sm text-gray-300">
                                    <div class="{{ $q->correct_answer == 'option_a' ? 'text-green-400 font-bold' : '' }}">A: {{ $q->option_a }}</div>
                                    <div class="{{ $q->correct_answer == 'option_b' ? 'text-green-400 font-bold' : '' }}">B: {{ $q->option_b }}</div>
                                    <div class="{{ $q->correct_answer == 'option_c' ? 'text-green-400 font-bold' : '' }}">C: {{ $q->option_c }}</div>
                                    <div class="{{ $q->correct_answer == 'option_d' ? 'text-green-400 font-bold' : '' }}">D: {{ $q->option_d }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    
    <div class="flex justify-between items-center mb-8 bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg">
        <div>
            <h1 class="text-3xl font-bold text-white mb-1">{{ $quiz->title }}</h1>
            <p class="text-gray-400">Code: <span class="text-yellow-400 font-mono text-xl tracking-widest">{{ $quiz->code }}</span></p>
        </div>
        <div class="text-right">
            <a href="{{ route('teacher.dashboard') }}" class="text-gray-400 hover:text-white text-sm underline mb-2 block">← Back to Dashboard</a>
            <button onclick="window.location.reload();" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition flex items-center gap-2">
                🔄 Refresh Scores
            </button>
        </div>
    </div>

    <div class="bg-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-2xl">
        <div class="p-4 bg-gradient-to-r from-purple-900 to-indigo-900 border-b border-gray-700">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                🏆 Live Results
                <span class="text-sm font-normal text-gray-300 ml-2">({{ $leaderboard->count() }} submissions)</span>
            </h2>
        </div>

        @if($leaderboard->isEmpty())
            <div class="p-12 text-center">
                <div class="text-6xl mb-4">⏳</div>
                <h3 class="text-xl text-white font-bold mb-2">Waiting for students...</h3>
                <p class="text-gray-400">Share the code <span class="text-yellow-400 font-mono">{{ $quiz->code }}</span> with your students.</p>
                <p class="text-gray-500 text-sm mt-2">Scores will appear here as soon as they submit.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-800 text-gray-400 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Rank</th>
                            <th class="px-6 py-4">Student Name</th>
                            <th class="px-6 py-4 text-center">Score</th>
                            <th class="px-6 py-4 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($leaderboard as $index => $result)
                            <tr class="hover:bg-gray-800 transition duration-150">
                                <td class="px-6 py-4 font-bold text-lg">
                                    @if($index == 0) 🥇
                                    @elseif($index == 1) 🥈
                                    @elseif($index == 2) 🥉
                                    @else <span class="text-gray-500 ml-2">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-white font-bold">{{ $result->user->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $result->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block bg-green-900 text-green-300 py-1 px-3 rounded-full text-sm font-bold border border-green-700">
                                        {{ $result->score }} / {{ $result->total_questions }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-500 text-sm">
                                    {{ $result->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
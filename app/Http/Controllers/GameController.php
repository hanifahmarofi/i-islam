<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity; 

class GameController extends Controller
{
    // This function is called every time a game round finishes
    public function completeRound(Request $request)
    {
        $user = auth()->user();

        // 1. Determine Points based on the Game Result
        // Default is 2 points (for Scramble, Hangman, Domino)
        $pointsToAdd = 2; 

        // If the request says 'win_match', give 5 points (for Match Pairs)
        if ($request->result === 'win_match') {
            $pointsToAdd = 5;
        }

        // 2. Update the 'total_points' column
        $user->increment('total_points', $pointsToAdd);
        
        // Optional: Also give XP if you use it
        // $user->increment('xp', 10);

        Activity::create([
    'user_id' => auth()->id(),
    'description' => 'has completed the Hangman game',
    'type' => 'game',   // <--- We added this
    ]);

        return response()->json([
            'status' => 'rewarded',
            'message' => "+$pointsToAdd Stars Added!",
            'total_stars' => $user->total_points // Send back the real total
        ]);
    }
}
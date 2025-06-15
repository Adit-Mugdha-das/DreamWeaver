<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Riddle;

class RiddleController extends Controller
{
    // Show next unsolved riddle
    public function index()
    {
        $user = Auth::user();

        $nextRiddle = Riddle::whereNotIn('id', function ($query) use ($user) {
            $query->select('riddle_id')
                  ->from('riddle_user')
                  ->where('user_id', $user->id);
        })->first();

        return view('dreams.riddle', compact('nextRiddle'));
    }

    // Handle riddle submission (AJAX)
    public function solve(Request $request, $riddleId)
    {
        $riddle = Riddle::findOrFail($riddleId);
        $userAnswer = strtolower(trim($request->input('answer')));
        $correctAnswer = strtolower($riddle->answer);

        if ($userAnswer === $correctAnswer) {
            DB::table('riddle_user')->updateOrInsert(
                ['user_id' => Auth::id(), 'riddle_id' => $riddle->id],
                ['is_solved' => true, 'updated_at' => now(), 'created_at' => now()]
            );

            return response()->json(['success' => true, 'message' => '✅ Correct! You solved the riddle.']);
        } else {
            return response()->json(['success' => false, 'message' => '❌ Wrong! Try again.']);
        }
    }
}

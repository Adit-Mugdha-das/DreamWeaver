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

    // Handle riddle submission
    public function solve(Request $request, $riddleId)
    {
        $riddle = Riddle::findOrFail($riddleId);
        $userAnswer = strtolower(trim($request->input('answer')));
        $correctAnswer = strtolower($riddle->answer);

        if ($userAnswer === $correctAnswer) {
            DB::table('riddle_user')->insert([
                'user_id' => Auth::id(),
                'riddle_id' => $riddle->id,
                'is_solved' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->route('riddles.index')->with('success', '✅ Correct! You solved the riddle.');
        } else {
            return back()->with('error', '❌ Not quite. Try again!');
        }
    }
}

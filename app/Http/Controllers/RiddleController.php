<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Riddle;

class RiddleController extends Controller
{
    // Show the riddle blade view
    public function index()
    {
        // Just load the view. The riddle will be loaded via AJAX.
        return view('dreams.riddle');
    }

    // Fetch the next unsolved riddle dynamically via AJAX
    public function next()
{
    $user = Auth::user();

    $nextRiddle = Riddle::whereNotIn('id', function ($query) use ($user) {
        $query->select('riddle_id')
              ->from('riddle_user')
              ->where('user_id', $user->id)
              ->where('is_solved', true); // ✅ Only skip solved ones
    })->inRandomOrder()->first(); // ✅ Add shuffle/randomize

    if ($nextRiddle) {
        return response()->json([
            'id' => $nextRiddle->id,
            'question' => $nextRiddle->question,
            'hint' => $nextRiddle->hint,
        ]);
    } else {
        return response()->json(['done' => true]);
    }
}


    // Handle answer submission via AJAX
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

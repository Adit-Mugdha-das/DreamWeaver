<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    // 🟡 Update comment content
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        // Ensure only the owner can edit
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->content = $request->content;
        $comment->save();

        return response()->json(['success' => true]);
    }

    // 🔴 Delete comment
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {

            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }
}

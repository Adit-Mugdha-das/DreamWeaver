<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dream;
use Illuminate\Support\Facades\Auth;

class MindMapController extends Controller
{
    public function show(Dream $dream)
    {
        abort_unless($dream->user_id === Auth::id(), 403);
        return view('dreams.mindmap', compact('dream'));
    }

    public function save(Request $r, Dream $dream)
    {
        abort_unless($dream->user_id === Auth::id(), 403);

        $dream->update(['mindmap_md' => (string) $r->input('mindmap_md')]);
        return back()->with('success', 'Mind map saved');
    }
}

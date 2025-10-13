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
        
        // Get all saved mind maps for this user
        $savedMindMaps = Dream::where('user_id', Auth::id())
            ->whereNotNull('mindmap_md')
            ->where('mindmap_md', '!=', '')
            ->orderBy('updated_at', 'desc')
            ->take(12)
            ->get(['id', 'title', 'mindmap_md', 'updated_at']);
        
        return view('dreams.mindmap', compact('dream', 'savedMindMaps'));
    }

    public function save(Request $r, Dream $dream)
    {
        abort_unless($dream->user_id === Auth::id(), 403);

        $dream->update(['mindmap_md' => (string) $r->input('mindmap_md')]);
        
        // Return JSON for AJAX requests
        if($r->ajax() || $r->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Mind map saved successfully!'
            ]);
        }
        
        return back()->with('success', 'Mind map saved');
    }
}

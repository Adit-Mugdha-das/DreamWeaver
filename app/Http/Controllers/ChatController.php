<?php
// app/Http/Controllers/ChatController.php
namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Notifications\MessageReceived; // we'll add below

class ChatController extends Controller
{
    // List my conversations
    public function index(Request $req) {
        $convs = $req->user()->load(['conversations.participants','conversations.messages' => function($q){
            $q->latest()->limit(1);
        }])->conversations()->latest('conversations.updated_at')->paginate(20);
        return view('chat.index', compact('convs'));
    }

    // Open (or create) a 1:1 conversation with $user
    public function open(User $user, Request $req) {
        abort_if($user->id === $req->user()->id, 404);

        // (Optional rule) allow only users who have shared at least one dream
        // if(!$user->dreams()->where('is_shared', true)->exists() || !$req->user()->dreams()->where('is_shared', true)->exists()) abort(403);

        $convId = DB::table('conversation_user as a')
            ->join('conversation_user as b','a.conversation_id','=','b.conversation_id')
            ->where('a.user_id',$req->user()->id)
            ->where('b.user_id',$user->id)
            ->value('a.conversation_id');

        $conv = $convId ? Conversation::find($convId) : DB::transaction(function() use ($user,$req) {
            $c = Conversation::create(['topic' => 'Direct Message']);
            $c->participants()->attach([$req->user()->id, $user->id]);
            return $c;
        });

        return view('chat.room', ['conversation' => $conv, 'other' => $user]);
    }

    // Fetch messages (for polling)
    public function fetch(Conversation $conversation, Request $req) {
        Gate::authorize('view', $conversation);
        $after = $req->query('after'); // message id
        $q = $conversation->messages()->with('user')->orderBy('id');
        if ($after) $q->where('id','>',(int)$after);
        $msgs = $q->take(50)->get();
        return response()->json($msgs);
    }

    // Send message
    public function send(Conversation $conversation, Request $req) {
        Gate::authorize('send', $conversation);
        $data = $req->validate(['body'=>'required|string|max:3000']);
        $msg  = $conversation->messages()->create([
            'user_id' => $req->user()->id,
            'body'    => $data['body'],
        ]);

        // Touch for sorting lists
        $conversation->touch();

        // Notify the other participant
        $other = $conversation->participants()->where('user_id','!=',$req->user()->id)->first();
        if ($other) $other->notify(new MessageReceived($msg));

        return response()->json($msg->load('user'));
    }

    // Mark read
    public function read(Conversation $conversation, Request $req) {
        Gate::authorize('view', $conversation);
        $conversation->messages()
            ->whereNull('read_at')
            ->where('user_id','!=',$req->user()->id)
            ->update(['read_at' => now()]);
        return response()->noContent();
    }
}

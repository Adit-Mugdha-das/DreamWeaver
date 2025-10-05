<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Chat</title>
  @vite('resources/css/app.css')
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-slate-100 antialiased">
  <div class="fixed inset-0 -z-10">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(6,182,212,0.15),transparent_70%)]"></div>
    <div class="absolute inset-0 opacity-[0.06] [mask-image:linear-gradient(to_bottom,black,transparent)]">
      <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg"><defs>
        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
          <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
        </pattern></defs><rect width="100%" height="100%" fill="url(#grid)"/></svg>
    </div>
  </div>

  <main
    x-data="chatRoom({
        fetchUrl: '{{ route('chat.fetch', $conversation) }}',
        sendUrl:  '{{ route('chat.send',  $conversation) }}',
        readUrl:  '{{ route('chat.read',  $conversation) }}',
        meId: {{ auth()->id() }}
     })"
    x-init="init()"
    class="mx-auto max-w-3xl p-4">

    <div class="flex items-center justify-between mb-3">
      <div class="flex items-center gap-2">
        <!-- NEW: Back button -->
        <button type="button"
                onclick="goBackToPrevious()"
                class="text-sm px-3 py-1 rounded-lg bg-white/5 hover:bg-white/10 ring-1 ring-white/10">
          ← Back
        </button>
        <div class="text-lg font-semibold ml-1">Chat with {{ $other->name }}</div>
      </div>

      <a href="{{ route('chat.index') }}" class="text-sm px-3 py-1 rounded-lg bg-white/5 hover:bg-white/10 ring-1 ring-white/10">All chats</a>
    </div>

    <div id="messages" class="h-[60vh] overflow-y-auto rounded-2xl p-4 bg-slate-900/60 ring-1 ring-white/10 space-y-2">
      <template x-for="m in messages" :key="m.id">
        <div class="max-w-[80%]" :class="m.user_id === meId ? 'ml-auto text-right' : ''">
          <div class="rounded-2xl px-4 py-2" :class="m.user_id === meId ? 'bg-cyan-600/30' : 'bg-white/5'">
            <div class="text-sm" x-text="m.body"></div>
            <div class="text-[10px] opacity-60 mt-1" x-text="new Date(m.created_at).toLocaleString()"></div>
          </div>
        </div>
      </template>
    </div>

    <form @submit.prevent="send()" class="mt-3 flex gap-2">
      @csrf
      <input x-model="draft" type="text" placeholder="Type a message..."
             class="flex-1 rounded-xl bg-slate-900/70 border border-white/10 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400/40">
      <button type="submit" class="rounded-xl px-4 py-2 bg-cyan-600 hover:bg-cyan-500">
        Send
      </button>
    </form>
  </main>

  <script>
  // NEW: Smart back handler (uses history; falls back to chat index)
  function goBackToPrevious() {
    // If we navigated here from another page in this tab, go back
    if (document.referrer && document.referrer !== window.location.href) {
      window.history.back();
    } else {
      // Fallback: go to the conversations list
      window.location.href = "{{ route('chat.index') }}";
    }
  }

  function chatRoom(cfg){
    return {
      messages: [],
      lastId: null,
      draft: '',
      meId: cfg.meId,
      init(){
        this.pull(); this.markRead();
        this.timer = setInterval(()=>this.pull(), 3000);
        window.addEventListener('beforeunload', ()=> clearInterval(this.timer));
        this.scrollDown();
      },
      async pull(){
        const url = this.lastId ? cfg.fetchUrl + '?after=' + this.lastId : cfg.fetchUrl;
        const res = await fetch(url);
        const data = await res.json();
        if(data.length){
          this.messages.push(...data);
          this.lastId = this.messages[this.messages.length-1].id;
          this.$nextTick(()=>this.scrollDown());
          this.markRead();
        }
      },
      async send(){
        if(!this.draft.trim()) return;
        const res = await fetch(cfg.sendUrl, {
          method: 'POST',
          headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'},
          body: JSON.stringify({body: this.draft})
        });
        const msg = await res.json();
        this.messages.push(msg);
        this.lastId = msg.id;
        this.draft = '';
        this.$nextTick(()=>this.scrollDown());
      },
      async markRead(){
        await fetch(cfg.readUrl, {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}});
      },
      scrollDown(){
        const box = document.getElementById('messages');
        if (box) box.scrollTop = box.scrollHeight;
      }
    }
  }
  </script>
</body>
</html>

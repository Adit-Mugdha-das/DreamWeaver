<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Chat</title>
  @vite('resources/css/app.css')
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    .msg-enter { transform: translateY(6px) scale(0.98); opacity: 0; }
    .msg-enter-active { transform: translateY(0) scale(1); opacity: 1; transition: transform .18s ease, opacity .18s ease; }

    /* Make the entire chat box taller and more immersive */
    #messages {
      height: 75vh !important; /* previously 60vh */
      max-height: calc(100vh - 180px);
    }
  </style>
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
    class="mx-auto max-w-4xl p-6 flex flex-col min-h-[90vh]">

    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-2">
        <button type="button"
                onclick="goBackToPrevious()"
                class="text-sm px-3 py-1 rounded-lg bg-white/5 hover:bg-white/10 ring-1 ring-white/10">
          ← Back
        </button>
        <div class="text-lg font-semibold ml-1">Chat with {{ $other->name }}</div>
      </div>
      <a href="{{ route('chat.index') }}" class="text-sm px-3 py-1 rounded-lg bg-white/5 hover:bg-white/10 ring-1 ring-white/10">All chats</a>
    </div>

    <div id="messages" class="flex-1 overflow-y-auto rounded-2xl p-5 bg-slate-900/70 ring-1 ring-white/10 space-y-3">
      <template x-for="m in messages" :key="(m.id ?? '') + '-' + (m.clientKey ?? '')">
        <div class="max-w-[80%] msg-enter"
             x-init="$el.classList.add('msg-enter'); requestAnimationFrame(()=>{$el.classList.add('msg-enter-active')})"
             :class="m.user_id === meId ? 'ml-auto text-right' : ''">
          <div class="rounded-2xl px-4 py-2"
               :class="m.user_id === meId ? 'bg-cyan-600/40' : 'bg-white/10'">
            <div class="text-sm break-words whitespace-pre-wrap" x-text="m.body ?? ''"></div>
            <div class="text-[10px] opacity-60 mt-1" x-text="formatTime(m.created_at)"></div>
          </div>
        </div>
      </template>
    </div>

    <form @submit.prevent="send()" class="mt-4 flex gap-3">
      @csrf
      <input x-model="draft" type="text" placeholder="Type a message..."
             class="flex-1 rounded-xl bg-slate-900/70 border border-white/10 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-400/40"
             autocomplete="off"
             @keydown.enter.prevent="send()">
      <button type="submit" class="rounded-xl px-6 py-3 bg-cyan-600 hover:bg-cyan-500">
        Send
      </button>
    </form>
  </main>

  <script>
  function goBackToPrevious() {
    if (document.referrer && document.referrer !== window.location.href) {
      window.history.back();
    } else {
      window.location.href = "{{ route('chat.index') }}";
    }
  }

  function chatRoom(cfg){
    return {
      messages: [],
      lastId: null,
      draft: '',
      meId: cfg.meId,
      timer: null,
      pausePollUntil: 0,
      seenIds: new Set(),

      init(){
        this.pull();
        this.markRead();
        this.timer = setInterval(()=> this.pull(), 3000);
        document.addEventListener('visibilitychange', () => { if (!document.hidden) this.pull(); });
        window.addEventListener('beforeunload', ()=> clearInterval(this.timer));
      },

      formatTime(ts) { try { return new Date(ts).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }); } catch { return ''; } },

      mergeServerMessages(list){
        const fresh = [];
        for (const m of list) {
          if (m && m.id != null && !this.seenIds.has(m.id)) {
            this.seenIds.add(m.id);
            if (typeof m.body !== 'string') m.body = m.body ?? '';
            fresh.push(m);
          }
        }
        if (fresh.length) {
          this.messages = [...this.messages, ...fresh];
          this.lastId = this.messages[this.messages.length - 1].id;
          this.$nextTick(()=> this.scrollDown());
        }
      },

      async pull(){
        if (Date.now() < this.pausePollUntil) return;
        try {
          const url = this.lastId ? cfg.fetchUrl + '?after=' + this.lastId : cfg.fetchUrl;
          const res = await fetch(url);
          const data = await res.json();
          if (Array.isArray(data) && data.length) {
            this.mergeServerMessages(data);
            this.markRead();
          }
        } catch (e) { console.warn('Pull failed', e); }
      },

      async send(){
        const text = this.draft.trim();
        if (!text) return;

        const tempId = 'temp-' + Date.now();
        const tempMsg = {
          id: tempId,
          clientKey: (crypto?.randomUUID?.() || String(performance.now())),
          user_id: this.meId,
          body: text,
          created_at: new Date().toISOString()
        };
        this.messages = [...this.messages, tempMsg];
        this.$nextTick(()=> this.scrollDown());
        this.draft = '';

        this.pausePollUntil = Date.now() + 800;

        try {
          const res = await fetch(cfg.sendUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ body: text })
          });
          const serverMsg = await res.json();
          if (typeof serverMsg.body !== 'string') serverMsg.body = text;

          if (serverMsg.id != null) this.seenIds.add(serverMsg.id);

          const replaced = this.messages.map(m => m.id === tempId ? { ...serverMsg, clientKey: (crypto?.randomUUID?.() || String(performance.now())) } : m);
          this.messages = replaced;
          this.lastId = serverMsg.id;
          this.$nextTick(()=> this.scrollDown());

        } catch (e) {
          this.messages = this.messages.filter(m => m.id !== tempId);
          this.draft = text;
          alert("Message couldn't be sent. Please try again.");
          console.error('Send failed', e);
        } finally {
          setTimeout(()=> { this.pausePollUntil = 0; }, 300);
        }
      },

      async markRead(){
        try { await fetch(cfg.readUrl, { method:'POST', headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}' }}); }
        catch (e) { /* noop */ }
      },

      scrollDown(){
        const box = document.getElementById('messages');
        if (box) box.scrollTo({ top: box.scrollHeight, behavior: 'smooth' });
      }
    }
  }
  </script>
</body>
</html>

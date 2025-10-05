<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🔔 Notifications | DreamWeaver</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="//unpkg.com/alpinejs" defer></script>

  <style>
    body {
      background: radial-gradient(circle at center, #0b0f1a 0%, #000 100%);
      font-family: 'Inter', sans-serif;
      color: #fff;
      min-height: 100vh;
      padding: 2rem;
    }
    .card {
      background-color: rgba(0, 0, 0, 0.55);
      border: 1px solid rgba(147, 51, 234, 0.2);
      border-radius: 1rem;
      padding: 1rem 1.25rem;
      backdrop-filter: blur(6px);
      box-shadow: 0 0 20px rgba(147, 51, 234, 0.08);
      transition: all .25s ease;
    }
    .card + .card { margin-top: .75rem; }
    .card:hover { background-color: rgba(30, 27, 75, 0.5); box-shadow: 0 0 30px rgba(147,51,234,.18); }
    .badge {
      background-color: #9333ea;
      padding: 0.25rem .6rem;
      font-size: .7rem;
      border-radius: 9999px;
      text-transform: uppercase;
      font-weight: 700;
      margin-right: .6rem;
      white-space: nowrap;
    }
    .back-btn {
      display: inline-flex; align-items: center; gap: .4rem;
      margin-bottom: 1.25rem; padding: .45rem 1rem;
      border-radius: 9999px; border: 1px solid rgba(255,255,255,.1);
      color:#fff; background: rgba(0,0,0,.6); text-decoration: none;
      transition: .2s;
    }
    .back-btn:hover{ background: rgba(255,255,255,.1); }
    .tabs { display:flex; gap:.5rem; margin: 1rem 0 1.25rem; }
    .tab {
      padding: .45rem .9rem; border-radius: 9999px; font-weight: 600; font-size: .9rem;
      border: 1px solid rgba(255,255,255,.08); background: rgba(255,255,255,.04); color:#cbd5e1;
    }
    .tab.active { background: rgba(147,51,234,.18); color:#fff; border-color: rgba(147,51,234,.35); }
    .section-title { font-size: 1rem; font-weight: 700; color:#a78bfa; margin: .5rem 0 .75rem; }
    .muted { color:#94a3b8; font-size:.8rem; }
    .link {
      color:#a78bfa; font-weight:600; text-decoration:none;
    }
    .link:hover { text-decoration: underline; }
  </style>
</head>
<body x-data="notifTabs()">

  <a href="{{ url('/welcome') }}" class="back-btn">← Back to Home</a>

  <div class="flex items-center justify-between">
    <h1 class="text-3xl font-bold text-violet-400 flex items-center gap-2">🔔 Your Notifications</h1>
  </div>

  @php
    // Split notifications into Chat vs Activity (current page only)
    $all = collect($notifications);
    $chat = $all->filter(function($n){ return ($n->data['type'] ?? null) === 'chat'; });
    $activity = $all->reject(function($n){ return ($n->data['type'] ?? null) === 'chat'; });
  @endphp

  <!-- Tabs -->
  <div class="tabs">
    <button class="tab" :class="tab==='activity' ? 'active' : ''" @click="tab='activity'">
      Activity <span class="ml-1 text-xs opacity-70">({{ $activity->count() }})</span>
    </button>
    <button class="tab" :class="tab==='chat' ? 'active' : ''" @click="tab='chat'">
      Chat <span class="ml-1 text-xs opacity-70">({{ $chat->count() }})</span>
    </button>
  </div>

  <!-- ACTIVITY LIST -->
  <section x-show="tab==='activity'">
    @if($activity->isEmpty())
      <p class="muted">No activity notifications.</p>
    @else
      <div class="section-title">Recent Activity</div>
      @foreach ($activity as $notification)
        @php
          $data = $notification->data ?? [];
          $type = strtoupper($data['type'] ?? 'GENERAL');
          $text = $data['message'] ?? $data['body_preview'] ?? $data['text'] ?? '(no message)';
        @endphp
        <div class="card">
          <div class="text-sm">
            <span class="badge">{{ $type }}</span>
            {{ $text }}
          </div>
          @if(isset($data['content']))
            <div class="muted mt-1">{{ $data['content'] }}</div>
          @endif
          <div class="muted mt-1">{{ $notification->created_at->diffForHumans() }}</div>
        </div>
      @endforeach
    @endif
  </section>

  <!-- CHAT LIST -->
  <section x-show="tab==='chat'">
    @if($chat->isEmpty())
      <p class="muted">No chat notifications.</p>
    @else
      <div class="section-title">Direct Messages</div>
      @foreach ($chat as $notification)
        @php
          $data = $notification->data ?? [];
          $preview = $data['message'] ?? $data['body_preview'] ?? '(no message)';
          $fromId = $data['from_user_id'] ?? null;
        @endphp
        <div class="card">
          <div class="text-sm">
            <span class="badge">CHAT</span>
            {{ $preview }}
          </div>
          <div class="mt-2">
            @if($fromId)
              <a class="link inline-flex items-center gap-1" href="{{ route('chat.open', $fromId) }}">💬 Open Chat</a>
            @endif
          </div>
          <div class="muted mt-1">{{ $notification->created_at->diffForHumans() }}</div>
        </div>
      @endforeach
    @endif
  </section>

  <!-- Pagination (same paginator controls you had before) -->
  <div class="mt-6">
    {{ method_exists($notifications, 'links') ? $notifications->links() : '' }}
  </div>

  <script>
    function notifTabs(){
      return { tab: 'activity' } // default tab
    }
  </script>
</body>
</html>

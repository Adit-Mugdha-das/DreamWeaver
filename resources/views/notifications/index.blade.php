<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🔔 Notifications | DreamWeaver</title>
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

    .notif-box {
      background-color: rgba(0, 0, 0, 0.55);
      border: 1px solid rgba(147, 51, 234, 0.2);
      border-radius: 1rem;
      padding: 1rem 1.5rem;
      margin-bottom: 1rem;
      backdrop-filter: blur(6px);
      box-shadow: 0 0 20px rgba(147, 51, 234, 0.1);
      transition: all 0.3s ease;
    }

    .notif-box:hover {
      background-color: rgba(30, 27, 75, 0.5);
      box-shadow: 0 0 30px rgba(147, 51, 234, 0.2);
    }

    .badge {
      background-color: #9333ea;
      padding: 0.25rem 0.6rem;
      font-size: 0.7rem;
      border-radius: 9999px;
      text-transform: uppercase;
      font-weight: bold;
      margin-right: 0.75rem;
    }

    .back-btn {
      display: inline-block;
      margin-bottom: 2rem;
      padding: 0.4rem 1rem;
      border-radius: 9999px;
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: #fff;
      background-color: rgba(0, 0, 0, 0.6);
      transition: 0.3s ease;
      text-decoration: none;
    }

    .back-btn:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }
  </style>
</head>
<body>

  <a href="{{ url('/welcome') }}" class="back-btn">← Back to Home</a>

  <h1 class="text-3xl font-bold text-violet-400 mb-6 flex items-center gap-2">🔔 Your Notifications</h1>

  @forelse ($notifications as $notification)
    <div class="notif-box">
      <div class="text-sm">
        <span class="badge">
          {{ strtoupper($notification->data['type']) }}
        </span>
        {{ $notification->data['message'] }}
      </div>

      @if(isset($notification->data['content']))
        <div class="text-xs text-gray-400 mt-1">
          {{ $notification->data['content'] }}
        </div>
      @endif

      <div class="text-xs text-gray-500 mt-1">
        {{ $notification->created_at->diffForHumans() }}
      </div>
    </div>
  @empty
    <p class="text-gray-400">You have no notifications yet.</p>
  @endforelse

</body>
</html>

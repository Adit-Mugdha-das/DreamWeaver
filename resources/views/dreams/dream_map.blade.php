<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Map</title>
  @vite('resources/css/app.css')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.cells.min.js"></script>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
      color: #f0e9ff;
      height: 100%;
      overflow: hidden;
    }

    #vanta-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
    }

    .content {
      position: relative;
      padding: 2rem;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(6px);
    }

    .zone {
      border: 1px solid rgba(167, 139, 250, 0.3);
      background: rgba(30, 27, 75, 0.85);
      border-radius: 1.5rem;
      padding: 2.9rem;
      transition: all 0.4s ease;
      box-shadow: inset 0 0 0.5px rgba(255,255,255,0.1), 0 0 20px rgba(192, 132, 252, 0.1);
      text-decoration: none;
    }

    .zone:hover {
      transform: scale(1.035);
      box-shadow: 0 0 25px #a855f7aa, 0 0 10px #7c3aed55;
      border-color: #a855f7;
    }

    .locked {
      background: rgba(31, 41, 55, 0.25);
      border-color: rgba(255, 0, 0, 0.2);
      opacity: 0.5;
      color: #ff5c5c;
    }

    .unlocked {
      background: rgba(88, 28, 135, 0.5);
      border-color: rgba(167, 139, 250, 0.4);
    }

    h1, h2 {
      color: #f0e9ff;
    }

    p {
      color: #bfaaff;
    }

    .text-green-400 {
      color: #00e0ff;
      font-weight: 600;
    }

    .text-red-400 {
      color: #ff5c5c;
    }

    a {
      color: #22d3ee;
      transition: all 0.3s ease;
      font-weight: 500;
    }

    a:hover {
  color: #67e8f9; /* or remove this too for no change */
  text-shadow: none;
}


    .top-left-button {
      position: absolute;
      top: 1.5rem;
      left: 1.5rem;
      background: linear-gradient(to right, #6d28d9, #4f46e5);
      padding: 0.6rem 1.2rem;
      border-radius: 0.5rem;
      color: white;
      font-weight: 600;
      font-size: 0.875rem;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      transition: transform 0.3s ease;
      z-index: 10;
      text-decoration: none;
    }

    .top-left-button:hover {
      transform: scale(1.05);
    }
  </style>
</head>
<body>
  <div id="vanta-bg"></div>

  <div class="content">
    <!-- Imagine Button -->
    <a href="{{ url('/imagine') }}" class="top-left-button">← Imagine</a>

    <h1 class="text-4xl font-bold text-white mb-12 drop-shadow-lg">🌌 Explore Your Dream Realm</h1>

    @php
      $areas = [
          'Forest of Forgotten Thoughts' => 'forest',
          'Sky Temple of Light' => 'sky',
          'Cloud of Lucid Realms' => 'cloud',
      ];

      $requirements = [
          'forest' => 'Fear Totem (Mask)',
          'sky' => 'Joy Totem (Wings)',
          'cloud' => 'Calm Totem (Mirror)',
      ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 max-w-6xl w-full px-4">
      @foreach($areas as $name => $key)
        @if($unlockedViaTotem[$key])
          <a href="/dream-world/{{ $key }}" class="zone unlocked block hover:cursor-pointer">
            <h2 class="text-2xl font-semibold mb-2">{{ $name }}</h2>
            <p class="text-sm text-purple-200 mb-2">Requires: {{ $requirements[$key] }}</p>
            <p class="text-green-400 font-medium">✅ Unlocked</p>
            <p class="text-blue-300 mt-2">✨ Explore</p>
          </a>
        @else
          <div class="zone locked">
            <h2 class="text-2xl font-semibold mb-2">{{ $name }}</h2>
            <p class="text-sm text-purple-200 mb-2">Requires: {{ $requirements[$key] }}</p>
            <p class="text-red-400 font-medium">🔒 Locked</p>
          </div>
        @endif
      @endforeach
    </div>
  </div>

  <script>
    VANTA.CELLS({
      el: "#vanta-bg",
      mouseControls: true,
      touchControls: true,
      color1: 0x8e44ad,
      color2: 0x1f1b2e,
      size: 2.00,
      speed: 1.5
    });
  </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Map</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')

  <!-- Vanta.js & Three.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.cells.min.js"></script>

  <!-- AOS CSS -->
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />

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
      position: relative;
      border-radius: 1.5rem;
      background-size: cover;
      background-position: center;
      overflow: hidden;
      padding: 2.9rem;
      color: white;
      text-align: left;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
      transition: all 0.3s ease-in-out;
      text-decoration: none;
    }

    .zone .overlay {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.45);
      z-index: 0;
    }

    .zone h2,
    .zone p {
      position: relative;
      z-index: 1;
      text-shadow: 0 1px 5px rgba(0,0,0,0.7);
    }

    .zone:hover {
      transform: scale(1.04);
      box-shadow: 0 0 30px rgba(148, 0, 211, 0.5);
    }

    .locked {
      filter: grayscale(70%) brightness(0.7);
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
      color: #67e8f9;
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
    <!-- Back Button -->
    <a href="{{ url('/imagine') }}" class="top-left-button">← Portal</a>

    <h1 class="text-4xl font-bold text-white mb-12 drop-shadow-lg" data-aos="zoom-in" data-aos-delay="200">🌌 Explore Your Dream Realm</h1>

    @php
      $areas = [
          [
              'name' => 'Forest of Forgotten Thoughts',
              'key' => 'forest',
              'requirement' => 'Fear Totem (Mask)',
              'image' => asset('images/forest.png')
          ],
          [
              'name' => 'Sky Temple of Light',
              'key' => 'sky',
              'requirement' => 'Joy Totem (Wings)',
              'image' => asset('images/sky.png')
          ],
          [
              'name' => 'Cloud of Lucid Realms',
              'key' => 'cloud',
              'requirement' => 'Calm Totem (Mirror)',
              'image' => asset('images/cloud.png')
          ]
      ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 max-w-6xl w-full px-4">
      @foreach($areas as $area)
        @php $unlocked = $unlockedViaTotem[$area['key']]; @endphp

        @if($unlocked)
          <a href="/dream-world/{{ $area['key'] }}"
             class="zone block {{ $unlocked ? 'unlocked' : 'locked' }}"
             style="background-image: url('{{ $area['image'] }}');"
             data-aos="fade-up"
             data-aos-delay="{{ $loop->index * 150 + 300 }}">
        @else
          <div class="zone locked"
               style="background-image: url('{{ $area['image'] }}');"
               data-aos="fade-up"
               data-aos-delay="{{ $loop->index * 150 + 300 }}">
        @endif

          <div class="overlay"></div>
          <h2 class="text-2xl font-semibold mb-2">{{ $area['name'] }}</h2>
          <p class="text-sm text-purple-200 mb-2">Requires: {{ $area['requirement'] }}</p>

          @if($unlocked)
            <p class="text-green-400 font-medium">✅ Unlocked</p>
            <p class="text-blue-300 mt-2">✨ Explore</p>
          @else
            <p class="text-red-400 font-medium">🔒 Locked</p>
          @endif

        @if($unlocked)
          </a>
        @else
          </div>
        @endif
      @endforeach
    </div>
  </div>

  <!-- Vanta Cells Init -->
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

  <!-- AOS JS -->
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      once: true,
      easing: 'ease-in-out'
    });
  </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream World Portal</title>
  @vite('resources/css/app.css')

  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #0f172a;
      color: white;
      font-family: 'Inter', sans-serif;
      overflow-x: hidden;
      min-height: 100vh;
    }

    #vanta-bg {
      position: fixed;
      width: 100%;
      height: 100%;
      z-index: -1;
      top: 0;
      left: 0;
    }

    .portal-heading {
      font-size: 2.8rem;
      font-weight: 800;
      letter-spacing: 1px;
      line-height: 1.3;
      text-align: center;
    }

    .portal-subtext {
      margin-top: 0.5rem;
      font-size: 1.2rem;
      color: #cbd5e1;
      text-align: center;
    }

    .home-btn {
      position: fixed;
      top: 1.5rem;
      left: 1.5rem;
      background: linear-gradient(135deg, #14b8a6, #ec4899);
      padding: 0.6rem 1.2rem;
      color: white;
      font-weight: 600;
      border-radius: 0.5rem;
      box-shadow: 0 0 10px rgba(255,255,255,0.1);
      text-decoration: none;
      transition: all 0.3s ease;
      z-index: 10;
    }

    .home-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 0 20px rgba(255,255,255,0.3);
    }

    .center-container {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 2rem;
    }

    .card-row {
      margin-top: 3rem;
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      justify-content: center;
    }

    .portal-card {
      position: relative;
      width: 260px;
      height: 380px;
      border-radius: 1.25rem;
      overflow: hidden;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      text-decoration: none;
      color: white;
      background-color: #1e293b;
    }

    .portal-card:hover {
      transform: scale(1.03);
      box-shadow: 0 12px 40px rgba(255, 255, 255, 0.2);
    }

    .portal-card img, .portal-card svg {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(0.8);
      transition: filter 0.3s ease;
      display: block;
    }

    .portal-card:hover img,
    .portal-card:hover svg {
      filter: brightness(1);
    }

    .card-overlay {
      position: absolute;
      bottom: 0;
      width: 100%;
      background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
      padding: 1rem;
      box-sizing: border-box;
    }

    .card-overlay h3 {
      margin: 0 0 0.5rem;
      font-size: 1.2rem;
      font-weight: 700;
    }

    .card-overlay p {
      margin: 0 0 1rem;
      font-size: 0.95rem;
      color: #cbd5e1;
    }

    .card-button {
      display: inline-block;
      padding: 0.4rem 0.8rem;
      background: #ec4899;
      color: white;
      font-size: 0.8rem;
      font-weight: 600;
      border-radius: 0.5rem;
      text-decoration: none;
      transition: background 0.3s ease;
    }

    .card-button:hover {
      background: #f472b6;
    }
  </style>
</head>
<body>
  <div id="vanta-bg"></div>

  <a href="{{ url('/welcome') }}" class="home-btn">🏠 Home</a>

  <div class="center-container" data-aos="fade-up">
    <div class="portal-heading" data-aos="zoom-in" data-aos-delay="100">Welcome to Your Dream World</div>
    <div class="portal-subtext" data-aos="fade-up" data-aos-delay="300">Wander through the magical realm your dreams have shaped...</div>

    @php
      // Compute a sensible Mind Map link:
      // 1) If user has dreams, open latest dream's mind map
      // 2) Else, send to your dreams index (adjust this route if different)
      $latestDream = \App\Models\Dream::where('user_id', auth()->id())->latest()->first();
      $mindmapUrl = $latestDream
          ? route('mindmap.show', $latestDream)
          : (Route::has('dreams.index') ? route('dreams.index') : url('/dreams'));
    @endphp

    <div class="card-row" data-aos="fade-up" data-aos-delay="600">
      <a href="http://127.0.0.1:8000/test-avatar" class="portal-card" data-aos="zoom-in" data-aos-delay="800">
        <img src="/images/avatar-icon.png" alt="Your Dream Avatar">
        <div class="card-overlay">
          <h3>Your Dream Avatar</h3>
          <p>Craft the persona born from your dreams.</p>
          <span class="card-button">Enter →</span>
        </div>
      </a>

      <a href="{{ route('totems') }}" class="portal-card" data-aos="zoom-in" data-aos-delay="1000">
        <img src="/images/totem-icon.png" alt="Dream Totems">
        <div class="card-overlay">
          <h3>Dream Totems</h3>
          <p>Discover powerful relics in your dreamscape.</p>
          <span class="card-button">Explore →</span>
        </div>
      </a>

      <a href="{{ route('dream.map') }}" class="portal-card" data-aos="zoom-in" data-aos-delay="1200">
        <img src="/images/map-icon.png" alt="Dream Map">
        <div class="card-overlay">
          <h3>Dream Map</h3>
          <p>Explore your dream world visually.</p>
          <span class="card-button">View →</span>
        </div>
      </a>

      <a href="{{ route('riddles.index') }}" class="portal-card" data-aos="zoom-in" data-aos-delay="1400">
        <img src="/images/riddle-icon.png" alt="Dream Riddles">
        <div class="card-overlay">
          <h3>Dream Riddles</h3>
          <p>Solve mystical puzzles whispered from the void.</p>
          <span class="card-button">Solve →</span>
        </div>
      </a>

      {{-- NEW: Mind Map card --}}
      <a href="{{ $mindmapUrl }}" class="portal-card" data-aos="zoom-in" data-aos-delay="1600">
        {{-- Inline SVG so you don’t need an image asset --}}
        <svg viewBox="0 0 640 480" preserveAspectRatio="xMidYMid slice" aria-label="Dream Mind Map">
          <defs>
            <linearGradient id="mmg" x1="0" x2="1" y1="0" y2="1">
              <stop offset="0%" stop-color="#14b8a6"/>
              <stop offset="100%" stop-color="#ec4899"/>
            </linearGradient>
          </defs>
          <rect width="100%" height="100%" fill="#0f172a"/>
          <g opacity="0.25">
            <circle cx="100" cy="100" r="60" fill="url(#mmg)"/>
            <circle cx="520" cy="140" r="80" fill="url(#mmg)"/>
            <circle cx="300" cy="360" r="90" fill="url(#mmg)"/>
          </g>
          <g stroke="url(#mmg)" stroke-width="6" fill="none">
            <path d="M120,110 C220,120 340,140 520,160"/>
            <path d="M300,360 C260,300 180,220 120,110"/>
            <path d="M520,160 C480,240 400,300 300,360"/>
          </g>
          <g fill="#ffffff">
            <circle cx="120" cy="110" r="10"/>
            <circle cx="520" cy="160" r="10"/>
            <circle cx="300" cy="360" r="10"/>
          </g>
        </svg>

        <div class="card-overlay">
          <h3>Dream Mind Map</h3>
          <p>Visualize and connect the symbols, places, and emotions in your dream.</p>
          <span class="card-button">Open →</span>
        </div>
      </a>
      {{-- /Mind Map card --}}
      
      {{-- NEW: Dream Art Generator card --}}
      @php
        $latestDreamForArt = \App\Models\Dream::where('user_id', auth()->id())->latest()->first();
        $artUrl = $latestDreamForArt
            ? route('dream.art.show', $latestDreamForArt)
            : (Route::has('dreams.index') ? route('dreams.index') : url('/dreams'));
      @endphp
      <a href="{{ $artUrl }}" class="portal-card" data-aos="zoom-in" data-aos-delay="1800">
        {{-- Inline SVG art palette icon --}}
        <svg viewBox="0 0 640 480" preserveAspectRatio="xMidYMid slice" aria-label="Dream Art Generator">
          <defs>
            <linearGradient id="artg" x1="0" x2="1" y1="0" y2="1">
              <stop offset="0%" stop-color="#ec4899"/>
              <stop offset="100%" stop-color="#8b5cf6"/>
            </linearGradient>
          </defs>
          <rect width="100%" height="100%" fill="#0f172a"/>
          <g opacity="0.3">
            <circle cx="200" cy="150" r="40" fill="#ef4444"/>
            <circle cx="300" cy="120" r="35" fill="#f59e0b"/>
            <circle cx="400" cy="140" r="45" fill="#10b981"/>
            <circle cx="250" cy="250" r="50" fill="#06b6d4"/>
            <circle cx="380" cy="280" r="40" fill="#8b5cf6"/>
            <circle cx="320" cy="350" r="38" fill="#ec4899"/>
          </g>
          <path d="M200,350 Q250,250 350,200 Q450,180 500,300" 
                stroke="url(#artg)" 
                stroke-width="8" 
                fill="none" 
                opacity="0.6"/>
          <circle cx="500" cy="300" r="15" fill="url(#artg)"/>
        </svg>

        <div class="card-overlay">
          <h3>Dream Art Generator</h3>
          <p>Generate AI art prompts from your dreams. Use with DALL-E, Midjourney, or Stable Diffusion.</p>
          <span class="card-button">Generate Prompt</span>
        </div>
      </a>
      {{-- /Dream Art card --}}
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.cells.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    VANTA.CELLS({
      el: "#vanta-bg",
      mouseControls: true,
      touchControls: true,
      gyroControls: false,
      minHeight: 200.00,
      minWidth: 200.00,
      scale: 1.0,
      scaleMobile: 1.0,
      color1: 0x14b8a6,
      color2: 0xec4899,
      size: 2.0,
      backgroundColor: 0x0f172a
    });

    AOS.init({
      once: true,
      duration: 800,
      easing: 'ease-out-cubic'
    });
  </script>
</body>
</html>

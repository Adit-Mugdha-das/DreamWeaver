<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream World Portal</title>
  @vite('resources/css/app.css')
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #0f172a;
      color: white;
      font-family: 'Inter', sans-serif;
      overflow-x: hidden;
      min-height: 100vh;
      text-align: center;
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
      margin-top: 4rem;
      font-size: 1.7rem;
      font-weight: bold;
    }

    .portal-subtext {
      margin-top: 0.5rem;
      font-size: 1rem;
      color: #cbd5e1;
    }

    .button-row {
      margin-top: 3rem;
      display: flex;
      justify-content: center;
      gap: 2rem;
      flex-wrap: wrap;
    }

    .portal-btn {
      padding: 1rem 2rem;
      background: linear-gradient(135deg, #14b8a6, #ec4899);
      color: white;
      border-radius: 0.75rem;
      font-weight: 600;
      box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
      transition: transform 0.3s ease;
      border: none;
    }

    .portal-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 0 25px rgba(255, 255, 255, 0.2);
    }

    /* 🌠 Floating Emoji Styles */
    .float-emoji {
      position: absolute;
      font-size: 1.5rem;
      animation: rise 2s ease-out forwards;
      pointer-events: none;
      z-index: 100;
    }

    @keyframes rise {
      from { transform: translateY(0); opacity: 1; }
      to { transform: translateY(-60px); opacity: 0; }
    }

    /* 🏠 Home button style */
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
  </style>
</head>
<body>
  <div id="vanta-bg"></div>

  <!-- 🏠 Home button top-left -->
  <a href="{{ url('/welcome') }}" class="home-btn">🏠 Home</a>

  <div class="portal-heading">🪄 Welcome to Your Dream World</div>
  <div class="portal-subtext">Wander through the magical realm your dreams have shaped...</div>

  <div class="button-row">
    <a href="http://127.0.0.1:8000/test-avatar" class="portal-btn">🎭 Your Dream Avatar</a>

    <a href="{{ route('totems') }}" class="portal-btn">🔮 Dream Totems</a>
    <a href="{{ route('dream.map') }}" class="portal-btn">🗺️ Dream Map</a>
  </div>

  <!-- 🔮 Background Script -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.cells.min.js"></script>
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
  </script>

  <!-- ✨ Floating Emoji Animation -->
  <script>
    const emojis = ['✨', '🪐', '🌙'];
    document.querySelectorAll('.portal-btn').forEach(btn => {
      btn.addEventListener('click', e => {
        const span = document.createElement('span');
        span.textContent = emojis[Math.floor(Math.random() * emojis.length)];
        span.className = 'float-emoji';
        span.style.left = `${e.clientX}px`;
        span.style.top = `${e.clientY}px`;
        document.body.appendChild(span);
        setTimeout(() => span.remove(), 2000);
      });
    });
  </script>
</body>
</html>

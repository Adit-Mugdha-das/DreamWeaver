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
      font-size: 2.8rem;
      font-weight: 800;
      letter-spacing: 1px;
      line-height: 1.3;
    }

    .portal-subtext {
      margin-top: 0.5rem;
      font-size: 1.2rem;
      color: #cbd5e1;
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
      justify-content: center;
      flex-wrap: wrap;
      gap: 2rem;
    }

    .portal-card {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 1rem;
      padding: 1.5rem;
      width: 260px;
      text-align: center;
      text-decoration: none;
      color: white;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 20px rgba(255, 255, 255, 0.05);
    }

    .portal-card:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 30px rgba(255, 255, 255, 0.2);
    }

    .card-icon {
      width: 60px;
      height: 60px;
      margin-bottom: 1rem;
    }

    .card-content h3 {
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .card-content p {
      font-size: 0.95rem;
      color: #cbd5e1;
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
  </style>
</head>
<body>
  <div id="vanta-bg"></div>

  <!-- 🏠 Home button top-left -->
  <a href="{{ url('/welcome') }}" class="home-btn">🏠 Home</a>

  <!-- 🌌 Portal Content -->
  <div class="center-container">
    <div class="portal-heading">Welcome to Your Dream World</div>
    <div class="portal-subtext">Wander through the magical realm your dreams have shaped...</div>

    <div class="card-row">
      <a href="http://127.0.0.1:8000/test-avatar" class="portal-card">
        <img src="/images/avatar-icon.png" alt="Avatar Icon" class="card-icon">
        <div class="card-content">
          <h3>Your Dream Avatar</h3>
          <p>Craft the persona born from your dreams.</p>
        </div>
      </a>
      <a href="{{ route('totems') }}" class="portal-card">
        <img src="/images/totem-icon.png" alt="Totem Icon" class="card-icon">
        <div class="card-content">
          <h3>Dream Totems</h3>
          <p>Discover powerful relics in your dreamscape.</p>
        </div>
      </a>
      <a href="{{ route('dream.map') }}" class="portal-card">
        <img src="/images/map-icon.png" alt="Map Icon" class="card-icon">
        <div class="card-content">
          <h3>Dream Map</h3>
          <p>Explore your dream world visually.</p>
        </div>
      </a>
    </div>
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
    document.querySelectorAll('.portal-card').forEach(card => {
      card.addEventListener('click', e => {
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

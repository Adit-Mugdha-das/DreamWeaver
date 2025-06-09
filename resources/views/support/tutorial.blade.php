<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DreamWeaver Tutorial</title>
  @vite('resources/css/app.css')
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Vanta & Three.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.fog.min.js"></script>

  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Inter', sans-serif;
      color: white;
      overflow-x: hidden;
      overflow-y: auto; /* ✅ Scroll enabled */
    }

    #vanta-bg {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      z-index: 0;
    }

    .back-link {
      position: fixed;
      top: 1.5rem;
      left: 1.5rem;
      z-index: 10;
      background-color: rgba(0, 255, 255, 0.08);
      border: 1px solid rgba(0, 255, 255, 0.2);
      padding: 0.5rem 1rem;
      font-size: 0.9rem;
      border-radius: 0.6rem;
      color: #38bdf8;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      backdrop-filter: blur(4px);
    }

    .back-link:hover {
      color: #ffffff;
      background-color: rgba(0, 255, 255, 0.15);
      transform: translateX(-2px);
    }

    .content {
      position: relative;
      z-index: 1;
      padding: 6rem 2rem 3rem; /* leave space for fixed Back button */
      max-width: 900px;
      margin: auto;
    }

    .container {
      background-color: rgba(0, 0, 0, 0.55);
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.05);
      animation: fadeIn 1s ease-out forwards;
    }

    h1 {
      font-size: 2rem;
      background: linear-gradient(to right, #a855f7, #06b6d4);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-weight: 800;
      margin-bottom: 1.5rem;
    }

    p {
      font-size: 1.1rem;
      color: #d1d5db;
      margin-bottom: 2rem;
    }

    iframe {
      width: 100%;
      aspect-ratio: 16 / 9;
      border-radius: 0.75rem;
      margin-bottom: 2rem;
      border: none;
    }

    ul {
      list-style-type: none;
      padding-left: 0;
    }

    li {
      margin-bottom: 0.8rem;
      font-size: 1rem;
      line-height: 1.6;
    }

    li strong {
      color: #93c5fd;
    }

    @keyframes fadeIn {
      0% {
        opacity: 0;
        transform: translateY(30px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 640px) {
      .container {
        padding: 1.2rem;
      }

      .back-link {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
      }
    }
  </style>
</head>
<body>

  <div id="vanta-bg"></div>

  <a href="{{ url()->previous() }}" class="back-link">← Back</a>

  <div class="content">
    <div class="container">
      <h1>🌟 DreamWeaver Full Project Tutorial</h1>

      <p>This tutorial explains how to use all the magical features of DreamWeaver:</p>

      <iframe src="https://www.youtube.com/embed/YOUR_VIDEO_ID" allowfullscreen></iframe>

      <ul>
        <li><strong>Analyze a Dream:</strong> Upload your dream and choose how to interpret it using AI.</li>
        <li><strong>Dream Patterns:</strong> See recurring dream emotions or elements visualized.</li>
        <li><strong>Dream Library:</strong> Explore poems, stories, myths, and echoes.</li>
        <li><strong>Enter Dream World:</strong> Step into themed realms like sky, forest, and clouds.</li>
        <li><strong>Shared Realm:</strong> Discover and engage with dreams shared by other dreamers.</li>
        <li><strong>Psychological Support:</strong> Access mental wellness resources when needed.</li>
      </ul>
    </div>
  </div>

  <script>
    VANTA.FOG({
      el: "#vanta-bg",
      mouseControls: true,
      touchControls: true,
      gyroControls: false,
      highlightColor: 0x00ffff,
      midtoneColor: 0x111111,
      lowlightColor: 0x000000,
      baseColor: 0x000000,
      blurFactor: 0.55,
      speed: 1.0,
      zoom: 0.8
    })
  </script>
</body>
</html>

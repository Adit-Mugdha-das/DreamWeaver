<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DreamWeaver - Home</title>
  @vite('resources/css/app.css')
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100vh;
      width: 100vw;
      overflow: hidden;
      font-family: 'Inter', sans-serif;
      background-color: #0a0c1b;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    canvas {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: 0;
    }

    .container {
      z-index: 1;
      text-align: center;
      max-width: 700px;
      width: 100%;
    }

    header {
      margin-bottom: 2.5rem;
    }

    header h1 {
      font-size: 2.5rem;
      font-weight: bold;
      background: linear-gradient(to right, #c084fc, #38bdf8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 0.3rem;
      animation: fadeSlideDown 1s ease-out forwards;
    }

    header p {
      font-size: 1.25rem;
      color: #9f8cff;
      font-weight: 500;
      opacity: 0;
      margin-bottom: 2rem;
      animation: fadeSlideLeft 1.2s ease-out forwards;
      animation-delay: 0.3s;
      transform: translateX(-60px);
    }

    .button-group {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      align-items: center;
      width: 100%;
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 1s ease-out forwards;
      animation-delay: 0.5s;
    }

    .button {
      padding: 0.85rem 2rem;
      border-radius: 0.75rem;
      font-size: 1.05rem;
      font-weight: 600;
      background-color: rgba(255, 255, 255, 0.08);
      color: #fff;
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.3s ease;
      width: 260px;
      text-align: center;
      backdrop-filter: blur(4px);
      box-shadow: 0 0 8px rgba(0, 255, 255, 0.15);
    }

    .button:hover {
      background-color: rgba(0, 255, 255, 0.08);
      color: #e0f7ff;
      box-shadow: 0 0 12px rgba(0, 255, 255, 0.35);
      transform: translateY(-0.3px);
    }

    @keyframes fadeSlideDown {
      0% { opacity: 0; transform: translateY(-120px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeSlideLeft {
      0% { opacity: 0; transform: translateX(-60px); }
      100% { opacity: 1; transform: translateX(0); }
    }

    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    .top-left-logout {
      position: fixed;
      top: 1.5rem;
      left: 1.5rem;
      z-index: 10;
    }

    .top-left-logout .button {
      padding: 0.6rem 1.2rem;
      font-size: 1rem;
      width: auto;
      min-width: unset;
      border-radius: 0.6rem;
    }

    .top-right-tutorial {
      position: fixed;
      top: 1.5rem;
      right: 1.5rem;
      z-index: 10;
    }

    .top-right-tutorial .button {
      padding: 0.6rem 1.2rem;
      font-size: 1rem;
      width: auto;
      min-width: unset;
      border-radius: 0.6rem;
      background-color: rgba(0, 255, 255, 0.08);
      border: 1px solid rgba(0, 255, 255, 0.2);
      box-shadow: 0 0 8px rgba(0, 255, 255, 0.15);
      color: #e0f7ff;
      transition: all 0.3s ease;
      backdrop-filter: blur(4px);
    }

    .top-right-tutorial .button:hover {
      background-color: rgba(0, 255, 255, 0.12);
      color: #ffffff;
      box-shadow: 0 0 12px rgba(0, 255, 255, 0.35);
      transform: translateY(-0.3px);
    }
  </style>
</head>
<body>

<!-- 🌌 Portal Intro Video -->
<div id="portal-intro" class="fixed inset-0 z-50">
  <video autoplay muted playsinline id="portalVideo" class="w-full h-full object-cover">
    <source src="{{ asset('videos/portal_intro.mp4') }}" type="video/mp4">
    Your browser does not support the video tag.
  </video>
  <div class="absolute inset-0 flex items-center justify-center text-fuchsia-300 text-xl font-semibold backdrop-blur-sm">
    Entering the Dream Realm...
  </div>
</div>

<!-- ✨ Background Canvas -->
<canvas id="nebula" class="opacity-0 transition-opacity duration-1000"></canvas>

<!-- 🌙 Main App Content -->
<div id="main-content" class="opacity-0 transition-opacity duration-1000">

  <form method="POST" action="{{ route('logout') }}" class="top-left-logout">
    @csrf
    <button type="submit" class="button">🚪 Logout</button>
  </form>

  <a href="{{ route('tutorial.show') }}" class="top-right-tutorial">
    <div class="button">📘 Tutorial</div>
  </a>

  <div class="container">
    <header>
      <h1>🌌 Welcome {{ Auth::user()->name }} to DreamWeaver</h1>
      <p>The magical World of Dreams</p>
    </header>

    <div class="button-group">
      <a href="{{ route('dreams.create') }}" class="button">Analyze a Dream</a>
      <a href="{{ route('dreams.index') }}" class="button">View Saved Dreams</a>
      <a href="{{ route('support') }}" class="button">Psychological Support</a>
      <a href="{{ route('dashboard') }}" class="button">Dream Patterns</a>
      <a href="{{ route('dreams.audio') }}" class="button">Guided Audio</a>
      <a href="{{ route('imagine.portal') }}" class="button">Enter Dream World</a>
      <a href="{{ route('library.index') }}" class="button">Dream Library</a>
      <a href="{{ route('dreams.shared') }}" class="button">Shared Realm</a>
    </div>
  </div>
</div>

<!-- 🌌 Nebula Background Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script>
  let scene, camera, renderer, particles;

  function init() {
    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 1, 1000);
    camera.position.z = 400;

    renderer = new THREE.WebGLRenderer({ canvas: document.getElementById('nebula'), alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight);

    const geometry = new THREE.BufferGeometry();
    const particleCount = 2000;
    const positions = [];

    for (let i = 0; i < particleCount; i++) {
      positions.push((Math.random() - 0.5) * 1000);
      positions.push((Math.random() - 0.5) * 1000);
      positions.push((Math.random() - 0.5) * 1000);
    }

    geometry.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
    const material = new THREE.PointsMaterial({
      color: 0x80dfff,
      size: 2,
      blending: THREE.AdditiveBlending,
      transparent: true
    });

    particles = new THREE.Points(geometry, material);
    scene.add(particles);
    animate();
  }

  function animate() {
    requestAnimationFrame(animate);
    particles.rotation.y += 0.0007;
    renderer.render(scene, camera);
  }

  window.addEventListener('resize', () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
  });

  init();

  // 🌌 Fade-in after video ends
  document.addEventListener("DOMContentLoaded", () => {
    const video = document.getElementById("portalVideo");
    video.onended = () => {
      document.getElementById("portal-intro").style.display = "none";
      document.getElementById("nebula").classList.remove("opacity-0");
      document.getElementById("nebula").classList.add("opacity-100");

      const content = document.getElementById("main-content");
      content.classList.remove("opacity-0");
      content.classList.add("opacity-100");
    };
  });
</script>

</body>
</html>

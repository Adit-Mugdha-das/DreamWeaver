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
      overflow: hidden;
      height: 100%;
      font-family: 'Inter', sans-serif;
      background-color: #0a0c1b;
      color: white;
    }

    canvas {
      position: absolute;
      top: 0;
      left: 0;
      z-index: 0;
    }

    .container {
      position: relative;
      z-index: 1;
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      transform: translateY(-90px);
    }

    .text-group {
      margin-bottom: 2.5rem;
    }

    h1 {
      font-size: 2.5rem;
      font-weight: bold;
      background: linear-gradient(to right, #c084fc, #38bdf8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 0.3rem;
      animation: fadeSlideDown 1s ease-out forwards;
    }

    .subtitle {
      font-size: 1.25rem;
      color: #9f8cff; /* Updated to a cooler violet-blue tone */
      opacity: 0;
      font-weight: 500;
      margin-bottom: 2rem;
      transform: translateX(-60px);
      animation: fadeSlideLeft 1.2s ease-out forwards;
      animation-delay: 0.3s;
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
      0% {
        opacity: 0;
        transform: translateY(-120px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeSlideLeft {
      0% {
        opacity: 0;
        transform: translateX(-60px);
      }
      100% {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>

<canvas id="nebula"></canvas>

<div class="container">
  <div class="text-group">
    <h1>🌌 Welcome to DreamWeaver</h1>
    <p class="subtitle">The magical World of Dreams</p>
  </div>
  <div class="button-group">
    <a href="{{ route('dreams.create') }}" class="button">Analyze a Dream</a>
    <a href="{{ route('dreams.index') }}" class="button">View Saved Dreams</a>
  </div>
</div>

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
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>All Dreams</title>
  @vite('resources/css/app.css')
  <style>
    html, body {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      overflow-y: auto;
      min-height: 100vh;
      background: #070c1f;
      font-family: 'Inter', sans-serif;
      color: white;
    }

    canvas {
      position: fixed;
      top: 0;
      left: 0;
      z-index: 0;
    }

    .wrapper {
      position: relative;
      z-index: 1;
      max-width: 800px;
      margin: 0 auto;
      padding: 3rem 1rem;
    }

    h1 {
      font-size: 2.2rem;
      font-weight: bold;
      text-align: center;
      margin-bottom: 2rem;
      text-shadow:
        0 0 4px #a855f7,
        0 0 10px #a855f7,
        0 0 20px #3b82f6,
        0 0 40px #3b82f6;
    }

    .dream-card {
      background-color: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.06);
      border-radius: 1rem;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 0 8px rgba(138, 92, 246, 0.08);
      transition: box-shadow 0.4s ease, transform 0.3s ease;
      cursor: pointer;
      opacity: 0;
      transform: translateY(30px);
      animation: fadeInUp 0.8s ease forwards;
    }

    .dream-card:hover {
      box-shadow:
        0 0 14px rgba(168, 85, 247, 0.3),
        0 0 24px rgba(59, 130, 246, 0.2),
        0 0 34px rgba(59, 130, 246, 0.1);
      transform: scale(1.015);
    }

    .dream-card h3 {
      color: #c084fc;
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 0.75rem;
    }

    .dream-card p {
      color: #d1d5db;
      margin-bottom: 0.75rem;
      line-height: 1.6;
    }

    .emotion-badge {
  display: inline-block;
  font-size: 0.875rem;
  font-weight: 700; /* stronger weight */
  padding: 0.35rem 0.85rem;
  border-radius: 9999px;
  background-color: rgba(109, 40, 217, 0.3); /* deeper indigo base */
  color: #f5f3ff; /* near-white bright text */
  border: 1px solid rgba(165, 180, 252, 0.5); /* lavender border */
  box-shadow: 0 0 10px rgba(139, 92, 246, 0.3);
  transition: all 0.3s ease;
  letter-spacing: 0.2px;
}

.emotion-badge:hover {
  background-color: rgba(139, 92, 246, 0.5);
  color: #fff;
  box-shadow: 0 0 14px rgba(139, 92, 246, 0.6);
  cursor: pointer;
}


    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(30px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>

<!-- Starfield Canvas -->
<canvas id="starfield"></canvas>

<!-- Dreams Wrapper -->
<div class="wrapper">
  <h1>🌌 All Dreams</h1>

  @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6">
      {{ session('success') }}
    </div>
  @endif

  @php $delay = 0; @endphp
  @foreach ($dreams as $dream)
    <div class="dream-card" style="animation-delay: {{ $delay }}s;">
      <h3>{{ $dream->title }}</h3>
      <p>{{ $dream->content }}</p>

      @if ($dream->emotion_summary)
        <span class="emotion-badge">Emotion: {{ $dream->emotion_summary }}</span>
      @else
        <span class="text-gray-400 text-sm">No emotion detected</span>
      @endif
    </div>
    @php $delay += 0.1; @endphp
  @endforeach
</div>

<!-- Three.js Starfield Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script>
  let scene, camera, renderer, stars;

  function initStars() {
    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 1, 1000);
    camera.position.z = 1;

    renderer = new THREE.WebGLRenderer({ canvas: document.getElementById('starfield'), alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setClearColor(0x070c1f, 1);

    const starGeometry = new THREE.BufferGeometry();
    const starCount = 6000;
    const starVertices = [];

    for (let i = 0; i < starCount; i++) {
      const x = THREE.MathUtils.randFloatSpread(800);
      const y = THREE.MathUtils.randFloatSpread(800);
      const z = THREE.MathUtils.randFloatSpread(800);
      starVertices.push(x, y, z);
    }

    starGeometry.setAttribute('position', new THREE.Float32BufferAttribute(starVertices, 3));
    const starMaterial = new THREE.PointsMaterial({ color: 0xff6ec7, size: 1.5 });
    stars = new THREE.Points(starGeometry, starMaterial);
    scene.add(stars);

    animate();
  }

  function animate() {
    requestAnimationFrame(animate);
    stars.rotation.x += 0.0005;
    stars.rotation.y += 0.0003;
    renderer.render(scene, camera);
  }

  window.addEventListener('resize', () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
  });

  initStars();
</script>
</body>
</html>

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
    }

    h1 {
      font-size: 2.5rem;
      font-weight: bold;
      background: linear-gradient(to right, #a855f7, #38bdf8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 0.3rem;
      transform: translateY(-120px);
    }

    .subtitle {
      font-size: 1.25rem;
      color: #c084fc;
      opacity: 0.85;
      font-weight: 500;
      margin-bottom: 2rem;
      transform: translateY(-120px);
    }

    .button-group {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      align-items: center;
      width: 100%;
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
  box-shadow: 0 0 8px rgba(0, 255, 255, 0.15); /* changed to match stars */
}

.button:hover {
  background-color: rgba(0, 255, 255, 0.08); /* soft glow color */
  color: #e0f7ff;
  box-shadow: 0 0 15px rgba(0, 255, 255, 0.4);
  transform: translateY(-2px); /* slight lift */
}


  </style>
</head>
<body>

<canvas id="nebula"></canvas>

<div class="container">
  <h1>🌌 Welcome to DreamWeaver</h1>
  <p class="subtitle">The magical World of Dreams</p>
  <div class="button-group">
    <a href="{{ route('dreams.create') }}" class="button">📝 Submit a Dream</a>
    <a href="{{ route('dreams.index') }}" class="button">📂 View Saved Dreams</a>
  </div>
</div>

<!-- Three.js + Nebula Background -->
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

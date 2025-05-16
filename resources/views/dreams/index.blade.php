<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>All Dreams</title>
  @vite('resources/css/app.css')
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
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
      font-weight: 700;
      padding: 0.35rem 0.85rem;
      border-radius: 9999px;
      background-color: rgba(109, 40, 217, 0.3);
      color: #f5f3ff;
      border: 1px solid rgba(165, 180, 252, 0.5);
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

    .back-button {
      position: fixed;
      top: 1.5rem;
      left: 1.5rem;
      z-index: 10;
      background: rgba(109, 40, 217, 0.2);
      color: #e0e7ff;
      padding: 0.5rem 1rem;
      border-radius: 9999px;
      font-weight: 600;
      font-size: 0.95rem;
      text-decoration: none;
      border: 1px solid rgba(109, 40, 217, 0.4);
      box-shadow: 0 0 6px rgba(139, 92, 246, 0.4);
      transition: all 0.3s ease;
    }

    .back-button:hover {
      background: rgba(139, 92, 246, 0.4);
      box-shadow: 0 0 12px rgba(139, 92, 246, 0.6);
      color: #fff;
    }
  </style>
</head>
<body>

<!-- Starfield Canvas -->
<canvas id="starfield"></canvas>

<!-- Back to Home Button -->
<a href="{{ route('welcome') }}" class="back-button">← Home</a>
<!-- Analyze Dream Button -->
<a href="{{ route('dreams.create') }}" class="back-button" style="top: 4.5rem;">
  📝 Analyze a Dream
</a>



<!-- Dreams Wrapper -->
<div class="wrapper">
  <h1>🌌 All Dreams</h1>

  @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6">
      {{ session('success') }}
    </div>
  @endif

    @foreach ($dreams as $dream)
  <div class="dream-card" id="dream-{{ $dream->id }}" data-aos="fade-up" data-aos-duration="800">

    <h3>{{ $dream->title }}</h3>
    <p>{{ $dream->content }}</p>

    @if ($dream->emotion_summary)
      <span class="emotion-badge">Emotion: {{ $dream->emotion_summary }}</span>
    @else
      <span class="text-gray-400 text-sm">No emotion detected</span>
    @endif

    @if ($dream->short_interpretation)
      <p class="mt-3 text-sm text-indigo-200">
        <strong class="text-indigo-400">Short Interpretation:</strong> {{ $dream->short_interpretation }}
      </p>
    @endif

    <form class="delete-form mt-4" data-id="{{ $dream->id }}">
  <button type="button" class="delete-btn" style="
    background: rgba(168, 85, 247, 0.15);
    color: #f5f3ff;
    padding: 0.35rem 0.85rem;
    font-size: 0.85rem;
    border-radius: 9999px;
    font-weight: 600;
    border: 1px solid rgba(168, 85, 247, 0.4);
    box-shadow: 0 0 8px rgba(168, 85, 247, 0.4);
    cursor: pointer;
    transition: background 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
  "
  onmouseover="this.style.background='rgba(168, 85, 247, 0.35)'; this.style.boxShadow='0 0 12px rgba(168, 85, 247, 0.7)'"
  onmouseout="this.style.background='rgba(168, 85, 247, 0.15)'; this.style.boxShadow='0 0 8px rgba(168, 85, 247, 0.4)'">
    🗑️ Delete
  </button>
</form>

  </div>
  @endforeach


</div>

<!-- JS Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
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

  AOS.init({ once: true });
  initStars();

</script>

<!-- ✅ Separate script tag for auto-hide -->
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const alert = document.querySelector('.bg-green-100');
    if (alert) {
      setTimeout(() => {
        alert.style.transition = 'opacity 0.5s ease-out';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
      }, 2000);
    }
  });
</script>

<script>
  document.querySelectorAll('.delete-form').forEach(form => {
    const btn = form.querySelector('.delete-btn');
    const dreamId = form.dataset.id;
    const card = document.getElementById(`dream-${dreamId}`);

    btn.addEventListener('click', async () => {
      if (!confirm('Are you sure you want to delete this dream?')) return;

      try {
        const res = await fetch(`/dreams/${dreamId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (!res.ok) throw new Error('Deletion failed');

        card.style.transition = 'opacity 0.5s ease-out';
        card.style.opacity = 0;
        setTimeout(() => card.remove(), 500);
      } catch (err) {
        alert('Error deleting the dream.');
      }
    });
  });
</script>


</body>
</html>

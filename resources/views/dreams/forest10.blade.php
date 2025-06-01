<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forest Realm – Epilogue</title>
  @vite('resources/css/app.css')
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      background: black;
      overflow: hidden;
    }

    video.bg {
      position: fixed;
      top: 50%;
      left: 50%;
      height: 100%;
      width: auto;
      transform: translate(-50%, -50%) scaleX(1.75);
      object-fit: cover;
      z-index: -1;
    }

    .back-to-portal,
    .prev-scene,
    .next-scene {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      color: #e0e7ff;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      text-decoration: none;
      font-weight: 500;
      border: 1px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(6px);
      transition: background 0.3s;
    }

    .back-to-portal:hover,
    .prev-scene:hover,
    .next-scene:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .back-to-portal {
      top: 1.2rem;
      left: 1.5rem;
    }

    .prev-scene {
      bottom: 1.5rem;
      left: 1.5rem;
    }

    .next-scene {
      bottom: 1.5rem;
      right: 1.5rem;
    }
  </style>
</head>
<body>

  <!-- Background Forest 10 Video -->
  <video autoplay loop muted playsinline class="bg">
    <source src="{{ asset('videos/forest10.mp4') }}" type="video/mp4">
    Your browser does not support the video tag.
  </video>

  <!-- Back to Portal -->
  <a href="{{ route('dream.map') }}" class="back-to-portal">← Back to Portal</a>

  <!-- Previous Scene -->
  <a href="{{ route('forest9') }}" class="prev-scene">← Previous Scene</a>

  <!-- Loop back to Forest Entrance -->
  <a href="{{ route('forest.entrance') }}" class="next-scene">↻ Restart Forest</a>

</body>
</html>

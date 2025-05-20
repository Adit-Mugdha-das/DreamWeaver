<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Totems</title>
  @vite('resources/css/app.css')
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Inter', sans-serif;
      background-color: #0f172a;
      color: white;
      overflow: hidden;
    }

    #vanta-bg {
      position: fixed;
      width: 100%;
      height: 100%;
      z-index: -1;
      top: 0;
      left: 0;
    }

    .content {
      padding: 2rem;
      text-align: center;
      position: relative;
      z-index: 1;
    }

    .token {
      padding: 1rem;
      border-radius: 0.5rem;
      margin: 0.5rem;
      background-color: #1e1b4b;
      display: inline-block;
      width: 120px;
      box-shadow: 0 0 10px #a78bfa;
      font-weight: bold;
    }

    a {
      color: #93c5fd;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div id="vanta-bg"></div>

  <div class="content">
    <h1 class="text-3xl font-bold mb-6">🧿 Your Dream Totems</h1>

    @if(count($tokens))
      <div class="flex flex-wrap justify-center gap-4">
        @foreach($tokens as $token)
          <div class="token">
            {{ ucfirst($token) }}
          </div>
        @endforeach
      </div>
    @else
      <p class="text-gray-400">You haven't collected any tokens yet. Submit a dream to begin!</p>
    @endif

    <div class="mt-10">
      <a href="{{ url('/welcome') }}">⬅ Back to Welcome</a>
    </div>
  </div>

  <!-- Vanta.js dependencies -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
  <script>
    VANTA.NET({
      el: "#vanta-bg",
      mouseControls: true,
      touchControls: true,
      minHeight: 200.00,
      minWidth: 200.00,
      scale: 1.00,
      scaleMobile: 1.00,
      color: 0x8e44ad,
      backgroundColor: 0x0f172a,
      points: 12.0,
      maxDistance: 22.0,
      spacing: 18.0
    })
  </script>
</body>
</html>

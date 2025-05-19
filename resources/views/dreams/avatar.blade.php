<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Avatar</title>
  @vite('resources/css/app.css')
  <style>
    body {
      background-color: #0f172a;
      color: white;
      font-family: 'Inter', sans-serif;
      text-align: center;
      padding: 2rem;
    }

    .home-btn {
      position: fixed;
      top: 1rem;
      left: 1rem;
      background: linear-gradient(to right, #14b8a6, #ec4899);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      font-weight: 600;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      transition: transform 0.3s ease;
    }

    .home-btn:hover {
      transform: scale(1.05);
    }

    .avatar-img-wrapper {
      width: 160px;
      height: 160px;
      border-radius: 50%;
      background: radial-gradient(circle, #1f2937, #0f172a);
      box-shadow: 0 0 25px rgba(255,255,255,0.15);
      padding: 0.75rem;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 2rem auto 1rem;
      animation: glow 3s ease-in-out infinite;
    }

    .avatar-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #ec4899;
    }

    @keyframes glow {
      0%, 100% { box-shadow: 0 0 15px #14b8a6; }
      50% { box-shadow: 0 0 25px #ec4899; }
    }

    .generate-btn {
      margin-top: 1.5rem;
      background: linear-gradient(to right, #14b8a6, #ec4899);
      color: white;
      padding: 0.6rem 1.5rem;
      border-radius: 0.5rem;
      font-weight: 600;
      transition: transform 0.3s ease;
    }

    .generate-btn:hover {
      transform: scale(1.05);
    }

    .meta-text {
      color: #cbd5e1;
      margin-bottom: 0.25rem;
    }

    .saved-avatars-title {
      margin-top: 3rem;
      font-size: 1.5rem;
      font-weight: bold;
      color: #38bdf8;
    }

    .avatar-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
      gap: 1.2rem;
      margin-top: 1.5rem;
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
    }

    .saved-avatar-card {
      background: #1e293b;
      padding: 1rem;
      border-radius: 0.75rem;
      transition: transform 0.3s;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .saved-avatar-card:hover {
      transform: scale(1.05);
    }

    .saved-avatar-img {
      width: 100%;
      border-radius: 9999px;
      border: 2px solid #ec4899;
      margin-bottom: 0.5rem;
    }

    .saved-meta {
      font-size: 0.875rem;
      color: #94a3b8;
    }
  </style>
</head>
<body>

  @php
    $imageMap = [
        'wings' => 'wings.png',
        'mask' => 'mask.png',
        'cloud' => 'cloud.png',
        'fire' => 'fire.png',
        'swirl' => 'swirl.png',
        'mirror' => 'mirror.png',
        'default' => 'unknown.png'
    ];
  @endphp

  <a href="{{ url('/welcome') }}" class="home-btn">🏠 Home</a>

  <h1 class="text-3xl font-bold mb-4">🎭 Your Dream Avatar</h1>
  <p class="text-green-400 mb-2">Avatar generated based on your last dream emotion.</p>

  @if(session('message'))
    <div class="text-green-400 font-semibold mb-4">
      {{ session('message') }}
    </div>
  @endif

  @if($avatar)
    @php
      $image = $imageMap[$avatar['item']] ?? $imageMap['default'];
    @endphp

    <div class="avatar-img-wrapper">
      <img src="{{ asset('avatar/' . $image) }}" alt="{{ $avatar['item'] }}" class="avatar-image">
    </div>

    <p class="meta-text">Color: <span class="capitalize">{{ $avatar['color'] }}</span></p>
    <p class="meta-text">Item: {{ ucfirst($avatar['item']) }}</p>
  @else
    <p class="text-gray-400">No avatar generated yet.</p>
  @endif

  <form method="POST" action="{{ url('/avatar/generate') }}">

    @csrf
    <button class="generate-btn">🔄 Generate Avatar From Dream Emotion</button>
  </form>

  @if(isset($savedAvatars) && count($savedAvatars) > 0)
    <h2 class="saved-avatars-title">🖼️ Saved Avatars</h2>
    <div class="avatar-grid">
      @foreach($savedAvatars as $saved)
        @php
          $savedImg = $imageMap[$saved['item']] ?? $imageMap['default'];
        @endphp
        <div class="saved-avatar-card">
          <img src="{{ asset('avatar/' . $savedImg) }}" class="saved-avatar-img" alt="Saved Avatar">
          <div class="saved-meta">Color: {{ ucfirst($saved['color']) }}</div>
          <div class="saved-meta">Item: {{ ucfirst($saved['item']) }}</div>
        </div>
      @endforeach
    </div>
  @endif

</body>
</html>

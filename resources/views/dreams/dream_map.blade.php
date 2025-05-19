<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Map</title>
  @vite('resources/css/app.css')
  <style>
    body {
      margin: 0;
      padding: 2rem;
      background-color: #0f172a;
      font-family: 'Inter', sans-serif;
      color: white;
    }

    .zone {
      border: 1px solid #c084fc;
      border-radius: 0.5rem;
      padding: 1.5rem;
      transition: all 0.3s ease;
    }

    .zone:hover {
      box-shadow: 0 0 20px rgba(192, 132, 252, 0.3);
    }

    .locked {
      background-color: #1f2937;
      opacity: 0.5;
    }

    .unlocked {
      background-color: #6b21a8;
    }
  </style>
</head>
<body>
  <div class="text-center">
    <h1 class="text-3xl font-bold mb-10">🌌 Explore Your Dream Realm</h1>

    @php
      $areas = [
          'Forest of Forgotten Thoughts' => 'fear',
          'Sky Temple of Light' => 'joy',
          'Cloud of Lucid Realms' => 'calm',
      ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
      @foreach($areas as $name => $emotion)
        <div class="zone {{ $unlocked[$emotion] ? 'unlocked' : 'locked' }}">
          <h2 class="text-xl font-semibold">{{ $name }}</h2>
          <p class="text-sm mt-1">Requires: <span class="capitalize">{{ $emotion }}</span></p>

          @if($unlocked[$emotion])
            <p class="mt-2 text-green-400 font-medium">✅ Unlocked</p>
          @else
            <p class="mt-2 text-red-400 font-medium">🔒 Locked</p>
          @endif
        </div>
      @endforeach
    </div>

    <div class="mt-10">
      <a href="{{ url('/welcome') }}" class="text-blue-300 underline">⬅ Back to Welcome</a>
    </div>
  </div>
</body>
</html>

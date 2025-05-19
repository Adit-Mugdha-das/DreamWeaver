<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Totems</title>
  @vite('resources/css/app.css')
  <style>
    body {
      background-color: #0f172a;
      font-family: 'Inter', sans-serif;
      color: white;
      padding: 2rem;
      text-align: center;
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
  </style>
</head>
<body>
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
    <a href="{{ url('/welcome') }}" class="text-blue-300 underline">⬅ Back to Welcome</a>
  </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Riddle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <style>
    body {
      background-color: #0f172a;
      color: white;
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .box {
      max-width: 600px;
      background: rgba(255, 255, 255, 0.05);
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 0 30px rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(10px);
    }
    input[type="text"] {
      background: white;
      color: black;
    }
  </style>
</head>
<body>

  <div class="box">
    @if(session('success'))
      <div class="mb-4 p-3 bg-green-600 rounded">{{ session('success') }}</div>
    @elseif(session('error'))
      <div class="mb-4 p-3 bg-red-600 rounded">{{ session('error') }}</div>
    @endif

    @if($nextRiddle)
      <h2 class="text-2xl font-bold mb-4">🔮 Dream Riddle</h2>
      <p class="mb-4">{{ $nextRiddle->question }}</p>

      <form method="POST" action="{{ route('riddles.solve', $nextRiddle->id) }}">
        @csrf
        <input type="text" name="answer" placeholder="Your answer..." required class="w-full p-2 rounded">
        <button type="submit" class="mt-3 px-4 py-2 bg-pink-500 hover:bg-pink-600 rounded">Submit</button>
      </form>
    @else
      <h2 class="text-xl font-semibold text-center">🎉 You've solved all riddles!</h2>
    @endif
  </div>

</body>
</html>

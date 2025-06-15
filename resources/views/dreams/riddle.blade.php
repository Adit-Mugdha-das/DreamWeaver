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
    }

    .box {
      max-width: 600px;
      background: rgba(255, 255, 255, 0.05);
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 0 30px rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(10px);
      margin: 10% auto;
      text-align: center;
      display: none;
      opacity: 0;
      animation: fadeIn 0.8s ease forwards;
    }

    @keyframes fadeIn {
      to {
        opacity: 1;
      }
    }

    .riddle-question {
      font-size: 1.1rem;
      margin: 1rem 0;
      line-height: 1.5;
      white-space: pre-wrap;
      min-height: 60px;
    }

    .back-btn {
      position: fixed;
      top: 1.5rem;
      left: 1.5rem;
      background: linear-gradient(135deg, #14b8a6, #ec4899);
      padding: 0.5rem 1rem;
      color: white;
      font-weight: 600;
      border-radius: 0.5rem;
      box-shadow: 0 0 10px rgba(255,255,255,0.1);
      text-decoration: none;
      transition: all 0.3s ease;
      z-index: 100;
    }

    .back-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 0 20px rgba(255,255,255,0.3);
    }

    #hintBox {
      opacity: 0;
      transform: translateY(10px);
      transition: opacity 0.5s ease, transform 0.5s ease;
    }

    #hintBox.show {
      opacity: 1;
      transform: translateY(0);
    }

    input[type="text"] {
      background: white;
      color: black;
    }

    #feedback {
      margin-top: 1rem;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <!-- 🔙 Back to Portal -->
  <a href="{{ url('/imagine') }}" class="back-btn">← Portal</a>

  <!-- 🧠 Generate Riddle -->
  <div class="flex flex-col justify-center items-center min-h-screen">
    <button onclick="startTyping()" class="mb-6 px-6 py-3 bg-pink-600 hover:bg-pink-700 rounded text-white font-semibold transition">✨ Generate Riddle</button>

    <div id="riddleBox" class="box">
      <h2 class="text-2xl font-bold mb-2">🧠 Dream Riddle</h2>
      <p id="typedText" class="riddle-question"></p>

      @if($nextRiddle && $nextRiddle->hint)
        <button onclick="toggleHint()" class="mt-3 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded text-sm">💡 Show Hint</button>
        <div id="hintBox" class="mt-3 text-indigo-300 text-sm">{{ $nextRiddle->hint }}</div>
      @endif

      <form id="riddleForm" class="mt-4">
        @csrf
        <input type="text" name="answer" id="answerInput" placeholder="Your answer..." required class="w-full p-2 rounded mb-3 text-black">
        <button type="submit" class="px-4 py-2 bg-pink-500 hover:bg-pink-600 rounded">Submit</button>
      </form>

      <div id="feedback" class="text-sm"></div>
    </div>
  </div>

  <!-- ✨ Script -->
  <script>
    const originalText = @json($nextRiddle?->question ?? '');
    const riddleId = @json($nextRiddle?->id ?? null);
    const riddleForm = document.getElementById('riddleForm');
    const feedback = document.getElementById('feedback');

    function startTyping() {
      const box = document.getElementById('riddleBox');
      const target = document.getElementById('typedText');

      if (!originalText || !box || !target) return;

      box.style.display = 'block';
      box.style.opacity = 1;
      target.textContent = '';
      feedback.textContent = '';

      let i = 0;
      const speed = 35;
      const interval = setInterval(() => {
        if (i < originalText.length) {
          target.textContent += originalText.charAt(i);
          i++;
        } else {
          clearInterval(interval);
        }
      }, speed);
    }

    function toggleHint() {
      const hintBox = document.getElementById('hintBox');
      if (hintBox) hintBox.classList.toggle('show');
    }

    // ✅ Handle submission without reload
    if (riddleForm) {
      riddleForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const answer = document.getElementById('answerInput').value;

        fetch("{{ url('/riddles') }}/" + riddleId + "/solve", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
          },
          body: JSON.stringify({ answer: answer })
        })
        .then(response => response.json())
        .then(data => {
          feedback.textContent = data.message;
          feedback.style.color = data.success ? '#4ade80' : '#f87171'; // green/red
        })
        .catch(error => {
          feedback.textContent = "Something went wrong!";
          feedback.style.color = '#f87171';
        });
      });
    }
  </script>
</body>
</html>

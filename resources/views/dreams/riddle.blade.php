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
      margin: 2rem auto;
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

<a href="{{ url('/imagine') }}" class="back-btn">← Portal</a>

<div class="flex flex-col justify-center items-center min-h-screen">
  <button id="generateBtn" class="mb-6 px-6 py-3 bg-pink-600 hover:bg-pink-700 rounded text-white font-semibold transition">✨ Generate Riddle</button>

  <div id="riddleBox" class="box">
    <h2 class="text-2xl font-bold mb-2">🧠 Dream Riddle</h2>
    <p id="typedText" class="riddle-question"></p>

    <button id="hintBtn" onclick="toggleHint()" class="mt-3 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded text-sm hidden">💡 Show Hint</button>
    <div id="hintBox" class="mt-3 text-indigo-300 text-sm"></div>

    <form id="riddleForm" class="mt-4">
      @csrf
      <input type="text" name="answer" id="answerInput" placeholder="Your answer..." required class="w-full p-2 rounded mb-3 text-black">
      <button type="submit" class="px-4 py-2 bg-pink-500 hover:bg-pink-600 rounded">Submit</button>
    </form>

    <!-- ✅ Skip Riddle Button -->
    <button type="button" onclick="skipRiddle()" class="mt-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 rounded text-black">⏭️ Skip Riddle</button>

    <div id="feedback" class="text-sm mt-2"></div>
  </div>
</div>

<script>
let currentRiddleId = null;

function startTyping(text) {
  const box = document.getElementById('riddleBox');
  const target = document.getElementById('typedText');
  const feedback = document.getElementById('feedback');

  box.style.display = 'block';
  box.style.opacity = 1;
  target.textContent = '';
  feedback.textContent = '';

  let i = 0;
  const speed = 35;
  const interval = setInterval(() => {
    if (i < text.length) {
      target.textContent += text.charAt(i);
      i++;
    } else {
      clearInterval(interval);
    }
  }, speed);
}

function toggleHint() {
  document.getElementById('hintBox')?.classList.toggle('show');
}

document.getElementById('generateBtn').addEventListener('click', fetchNextRiddle);

function fetchNextRiddle() {
  fetch("{{ route('riddles.next') }}", {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  })
  .then(res => res.json())
  .then(data => {
    if (!data.id) {
      document.getElementById('typedText').textContent = "🎉 You've solved all riddles!";
      document.getElementById('hintBox').textContent = '';
      document.getElementById('hintBtn').classList.add('hidden');
      document.getElementById('riddleForm').style.display = 'none';
      return;
    }

    currentRiddleId = data.id;
    startTyping(data.question);

    document.getElementById('riddleForm').style.display = 'block';
    document.getElementById('answerInput').value = '';
    document.getElementById('answerInput').disabled = false;
    document.querySelector('#riddleForm button').disabled = false;

    if (data.hint) {
      document.getElementById('hintBox').textContent = data.hint;
      document.getElementById('hintBtn').classList.remove('hidden');
    } else {
      document.getElementById('hintBox').textContent = '';
      document.getElementById('hintBtn').classList.add('hidden');
    }

    document.getElementById('hintBox').classList.remove('show');
  });
}

document.getElementById('riddleForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const answer = document.getElementById('answerInput').value.trim();
  const feedback = document.getElementById('feedback');
  const submitButton = document.querySelector('#riddleForm button');

  if (!currentRiddleId || !answer) {
    feedback.textContent = "Please enter an answer.";
    feedback.style.color = "#f87171";
    return;
  }

  submitButton.disabled = true;
  feedback.textContent = '';

  fetch(`/riddles/${currentRiddleId}/solve`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ answer: answer })
  })
  .then(res => res.json())
  .then(data => {
    feedback.textContent = data.message;
    feedback.style.color = data.success ? "#4ade80" : "#f87171";

    if (data.success) {
      setTimeout(() => fetchNextRiddle(), 1000);
    } else {
      submitButton.disabled = false;
      document.getElementById('answerInput').focus();
    }
  })
  .catch(() => {
    feedback.textContent = "Something went wrong.";
    feedback.style.color = "#f87171";
    submitButton.disabled = false;
  });
});

// ✅ Skip Riddle logic
function skipRiddle() {
  fetch("{{ route('riddles.next') }}", {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  })
  .then(res => res.json())
  .then(data => {
    const target = document.getElementById('typedText');
    const feedback = document.getElementById('feedback');

    if (!data.id) {
      target.textContent = "✨ You've reached the end!";
      document.getElementById('hintBox').textContent = '';
      document.getElementById('hintBtn').classList.add('hidden');
      document.getElementById('riddleForm').style.display = 'none';
      return;
    }

    currentRiddleId = data.id;
    startTyping(data.question);
    feedback.textContent = '';

    document.getElementById('riddleForm').style.display = 'block';
    document.getElementById('answerInput').value = '';
    document.getElementById('answerInput').disabled = false;
    document.querySelector('#riddleForm button').disabled = false;

    if (data.hint) {
      document.getElementById('hintBox').textContent = data.hint;
      document.getElementById('hintBtn').classList.remove('hidden');
    } else {
      document.getElementById('hintBox').textContent = '';
      document.getElementById('hintBtn').classList.add('hidden');
    }

    document.getElementById('hintBox').classList.remove('show');
  });
}
</script>

</body>
</html>

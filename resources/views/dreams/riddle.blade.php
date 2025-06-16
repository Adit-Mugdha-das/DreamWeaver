<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Riddle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')

  <!-- Vanta.js & Three.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>

  <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      font-family: 'Inter', sans-serif;
      color: white;
      background-color: #000000; /* Pure black background */
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

    .flex {
      z-index: 1;
      position: relative;
    }

    .box {
      max-width: 600px;
      background: rgba(255, 255, 255, 0.05);
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 0 25px rgba(255, 102, 204, 0.3);
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
  background: linear-gradient(to right, #d946ef, #9333ea);
  padding: 0.5rem 1rem;
  color: white;
  font-weight: 600;
  border-radius: 0.5rem;
  box-shadow: 0 0 15px rgba(255, 102, 204, 0.3);
  text-decoration: none;
  transition: all 0.3s ease;
  z-index: 100;
  border: none;
}

.back-btn:hover {
  transform: scale(1.05);
  box-shadow: 0 0 25px rgba(255, 102, 204, 0.6);
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

    button {
      transition: all 0.3s ease;
      font-weight: 600;
      border: none;
    }

    #generateBtn {
  background: linear-gradient(to right, #ec4899, #8b5cf6);
  color: white;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  border-radius: 0.5rem;
  box-shadow: 0 0 12px rgba(255, 102, 204, 0.3);
  border: none;
}

#generateBtn:hover {
  background: linear-gradient(to right, #f472b6, #a78bfa);
  box-shadow: 0 0 18px rgba(255, 102, 204, 0.5);
}

    #hintBtn {
      background: linear-gradient(to right, #6366f1, #7c3aed);
      color: white;
    }

    #hintBtn:hover {
      background: linear-gradient(to right, #818cf8, #a78bfa);
    }

    button[type="submit"] {
      background: linear-gradient(to right, #f472b6, #ec4899);
      color: white;
    }

    button[type="submit"]:hover {
      background: linear-gradient(to right, #fb7185, #f43f5e);
    }

    button[onclick="skipRiddle()"] {
  background: linear-gradient(to right, #4f46e5, #6d28d9);
  color: white;
}

button[onclick="skipRiddle()"]:hover {
  background: linear-gradient(to right, #6366f1, #8b5cf6);
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

<div id="vanta-bg"></div>

<a href="{{ url('/imagine') }}" class="back-btn">← Portal</a>

<div class="flex flex-col justify-center items-center min-h-screen">
  <button id="generateBtn" class="mb-6 px-6 py-3 rounded text-white font-semibold"> Generate Riddle</button>

  <div id="riddleBox" class="box">
    <h2 class="text-2xl font-bold mb-2"> Dream Riddle</h2>
    <p id="typedText" class="riddle-question"></p>

    <button id="hintBtn" onclick="toggleHint()" class="mt-3 px-4 py-2 rounded text-sm hidden"> Show Hint</button>
    <div id="hintBox" class="mt-3 text-indigo-300 text-sm"></div>

    <form id="riddleForm" class="mt-4">
      @csrf
      <input type="text" name="answer" id="answerInput" placeholder="Your answer..." required class="w-full p-2 rounded mb-3">
      <button type="submit" class="px-4 py-2 rounded">Submit</button>
    </form>

    <button type="button" onclick="skipRiddle()" class="mt-2 px-4 py-2 rounded"> Skip Riddle</button>

    <div id="feedback" class="text-sm mt-2"></div>
  </div>
</div>

<script>
VANTA.NET({
  el: "#vanta-bg",
  mouseControls: true,
  touchControls: true,
  minHeight: 200.00,
  minWidth: 200.00,
  scale: 1.0,
  scaleMobile: 1.0,
  color: 0xff69b4, // Pure pink lines
  backgroundColor: 0x000000, // Pure black background
  points: 10.0,
  maxDistance: 20.0,
  spacing: 18.0
});


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
      document.getElementById('typedText').textContent = " You've solved all riddles!";
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

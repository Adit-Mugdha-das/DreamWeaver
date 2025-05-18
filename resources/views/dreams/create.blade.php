<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Dream</title>
  @vite('resources/css/app.css')
  <style>
    html, body {
      margin: 0;
      padding: 0;
      overflow: hidden;
      height: 100%;
      background: radial-gradient(circle at center, #0b0f1a 0%, #000 100%);
      font-family: 'Inter', sans-serif;
    }

    canvas {
      position: absolute;
      top: 0;
      left: 0;
      z-index: 0;
    }

    .form-wrapper {
      position: relative;
      z-index: 1;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .form-container {
      display: flex;
      align-items: flex-start;
      justify-content: center;
      gap: 2rem;
      width: 100%;
      max-width: 1200px;
      transition: all 0.8s ease;
    }

    .form-box, .result-box {
    flex: 1;
    max-width: 550px;
    width: 100%;
    min-width: 320px;
    padding: 2rem;
    border-radius: 1rem;
    background-color: rgba(0, 0, 0, 0.6); /* transparent black */
    box-shadow: 0 0 20px rgba(168, 85, 247, 0.3); /* soft purple glow */
    color: #ffffff;
    transform: translateX(0); /* initially center */
    transition: transform 0.8s ease, opacity 0.8s ease;
  }

  .result-card {
    background-color: rgba(0, 0, 0, 0.55); /* transparent black */
    border: 1px solid rgba(168, 85, 247, 0.3); /* soft purple glow */
    padding: 1rem;
    border-radius: 0.75rem;
    box-shadow: 0 0 15px rgba(168, 85, 247, 0.1);
  }



    .form-box {
      transform: translateX(0);
      transition: transform 0.8s ease;
    }

    .form-box.shift-left {
  transform: scale(0.95) translateX(-20px);
  opacity: 0.5;
  filter: blur(1.1px); /* ⬅️ Add this line */
  pointer-events: none;
}



    .result-box {
    position: absolute;
    right: 0;
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.8s ease;
    pointer-events: none; /* prevents accidental clicks while hidden */
  }

  .result-box.slide-in {
    position: relative;
    transform: translateX(0);
    opacity: 1;
    pointer-events: auto;
  }

  #resultText {
  white-space: pre-wrap;
  word-wrap: break-word;
  overflow-wrap: break-word;
  line-height: 1.6;
}



    @keyframes typing {
      to {
        width: 100%;
      }
    }

    @keyframes blink {
      50% {
        border-color: transparent;
      }
    }

    label {
      font-weight: 600;
    }

    input, textarea {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.15);
      color: #ffffff;
      width: 100%;
      padding: 0.75rem;
      margin-bottom: 1rem;
      border-radius: 0.5rem;
      font-size: 1rem;
      display: block;
    }

    input::placeholder,
    textarea::placeholder {
      color: #cccccc;
    }

    button {
    background-color: #D1D5DB; /* light silver */
    color: #000000;            /* black text */
    padding: 0.75rem;
    width: 100%;
    border: none;
    border-radius: 0.5rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  button:hover {
  background-color: #a78bfa; /* soft purple (Tailwind violet-400) */
  color: #000; /* keep black text or change to white if needed */
}

/* Make result-box buttons smaller */
.small-buttons .card {
  padding: 0.5rem 0.75rem;
  font-size: 0.85rem;
  border-radius: 0.4rem;
}


    .card {
      background-color: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 0.5rem;
      padding: 1rem;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .card:hover, .card.selected {
      background-color: rgba(255, 255, 255, 0.2);
      border-color: #a855f7;
    }

    .card-disabled {
  opacity: 0.4;
  pointer-events: none;
  cursor: not-allowed;
  border-color: rgba(255, 255, 255, 0.1);
}


    


    #loadingSpinner {
    display: none;
    justify-content: center;
    align-items: center;
    margin-top: 1rem;
  }

  .spinner-wrapper {
  display: none;
  justify-content: center;
  align-items: center;
  margin-top: 1rem;
}

  .spinner {
    border: 4px solid rgba(255, 255, 255, 0.1);
    border-left-color: #a855f7;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    animation: spin 1s linear infinite;
  }


    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }
    #saveAnimation {
    opacity: 0;
    transition: opacity 0.8s ease-in-out;
    background-color: rgba(168, 85, 247, 0.1); /* subtle purple glow */
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    color: #d8b4fe; /* soft violet text */
    font-weight: 500;
    backdrop-filter: blur(4px);
    box-shadow: 0 0 10px rgba(168, 85, 247, 0.3);
    margin-top: 1rem;
  }


    @keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(40px) scale(0.95);
  }
  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.form-box.animate-in {
  animation: fadeInUp 1.2s ease-out forwards;
  opacity: 0; /* Start transparent */
}

.top-buttons {
  position: fixed;
  top: 1.5rem;
  left: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  z-index: 100;
}

.nav-button {
  display: inline-block;
  padding: 0.4rem 1rem;
  font-size: 0.85rem;
  font-weight: 600;
  border-radius: 9999px;
  text-decoration: none;
  background-color: rgba(0, 0, 0, 0.85); /* deep black */
  color: #fff;
  border: 1px solid rgba(255, 255, 255, 0.15);
  box-shadow: 0 0 6px rgba(255, 255, 255, 0.1);
  transition: all 0.3s ease;
}

.nav-button:hover {
  background-color: rgba(15, 15, 15, 0.95); /* slightly deeper on hover */
  box-shadow: 0 0 12px rgba(255, 255, 255, 0.2);
  color: #ffffff;
}



  </style>
</head>
<body>

<canvas id="galaxyCanvas"></canvas>
<!-- Top Navigation Buttons -->
<div class="top-buttons">
  <a href="{{ route('welcome') }}" class="nav-button">🏠 Home</a>
  <a href="{{ route('dreams.index') }}" class="nav-button">📂 View Saved</a>
</div>


<div class="form-wrapper">
  <div class="form-container" id="formContainer">
    <div class="form-box animate-in" id="dreamFormBox">

      <h1 class="text-2xl font-bold text-center mb-6">💫 Submit Your Dream</h1>
      <form id="dreamForm">
        @csrf
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" placeholder="Dream title..." required>

        <label for="content">Dream Content:</label>
        <textarea id="content" name="content" rows="5" placeholder="Describe your dream..." required></textarea>

        <p class="font-semibold mb-2">Select Interpretation Type:</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
          <div class="card" data-type="emotion">🧠 Emotion Detection (Gemini)</div>
          <div class="card" data-type="story">📖 Story Generation (GPT-3.5)</div>
          <div class="card" data-type="short">✍️ Short Interpretation (Gemini)</div>
          <div class="card" data-type="long">🪄 Long Narrative (GPT-4)</div>
        </div>

        <input type="hidden" id="interpretationType" name="interpretationType" required>
        <button type="submit">Submit</button>
      </form>
      <!-- Place under dreamForm -->
<div id="interpretLoadingSpinner" class="spinner-wrapper">
  <div class="spinner"></div>
</div>

    </div>


    <div class="result-box" id="resultBox">
    <h2 class="text-lg font-semibold mb-2">💭 Interpretation Result</h2>

    <!-- This container will hold all stacked results -->
    <div id="allResults" class="space-y-4 mb-4"></div>

    <!-- Buttons to add more interpretations -->
    <div id="extraOptions" class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4 small-buttons">
      <div class="card" data-type="emotion">🧠 Emotion Detection (Gemini)</div>
      <div class="card" data-type="story">📖 Story Generation (GPT-3.5)</div>
      <div class="card" data-type="short">✍️ Short Interpretation (Gemini)</div>
      <div class="card" data-type="long">🪄 Long Narrative (GPT-4)</div>
    </div>

    <form id="saveDreamForm" method="POST" action="{{ route('dreams.store') }}">
      @csrf
      <input type="hidden" name="title" id="saveTitle">
      <input type="hidden" name="content" id="saveContent">
      <input type="hidden" name="short_interpretation" id="shortInterpretation">
      <input type="hidden" name="emotion_summary" id="emotionSummary">

      <button class="mt-4" type="submit">Save This Dream</button>
    </form>

    <button class="mt-4" onclick="goBack()">← Back to Form</button>

    <div id="saveAnimation" class="mt-4 hidden animate-pulse text-sm text-green-400 text-center">
      ✨ Dream saved!
    </div>

    <div id="saveLoadingSpinner" class="spinner-wrapper">
      <div class="spinner"></div>
    </div>
  </div>

  </div>
</div>



<script>
  const cards = document.querySelectorAll('.card');
  const hiddenField = document.getElementById('interpretationType');
  const dreamForm = document.getElementById('dreamForm');
  const formBox = document.getElementById('dreamFormBox');
  const resultBox = document.getElementById('resultBox');
  const usedTypes = new Set();
  const resultText = document.getElementById('resultText');
  const titleInput = document.getElementById('title');
  const contentInput = document.getElementById('content');

  cards.forEach(card => {
    card.addEventListener('click', () => {
      cards.forEach(c => c.classList.remove('selected'));
      card.classList.add('selected');
      hiddenField.value = card.dataset.type;
    });
  });

  function goBack() {
  formBox.classList.remove('shift-left');
  formBox.style.filter = 'none';
  resultBox.classList.remove('slide-in');

  titleInput.value = '';
  contentInput.value = '';
  hiddenField.value = '';
  document.getElementById('saveTitle').value = '';
  document.getElementById('saveContent').value = '';
  document.getElementById('shortInterpretation').value = '';
  document.getElementById('emotionSummary').value = '';

  usedTypes.clear();
  document.getElementById('allResults').innerHTML = '';
  cards.forEach(c => c.classList.remove('selected'));
}


  dreamForm.addEventListener('submit', async (e) => {
  e.preventDefault();

  const title = titleInput.value;
  const content = contentInput.value;
  const type = hiddenField.value;

  if (!type) {
    alert('Please select an interpretation type.');
    return;
  }

  // Show spinner
  document.getElementById('interpretLoadingSpinner').style.display = 'flex';

  try {
    const response = await fetch('/dreams/interpret', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({ title, content, type })
    });

    if (!response.ok) throw new Error('Failed to interpret dream.');

    const data = await response.json();

    document.getElementById('saveTitle').value = title;

    usedTypes.clear(); // Clear previous types
    document.getElementById('allResults').innerHTML = ''; // Clear old stacked results
    await fetchAndAppendResult(title, content, type); // Fetch and show first interpretation
    // Start typing


    formBox.classList.add('shift-left');
    resultBox.classList.add('slide-in');

    

  } catch (err) {
    alert('Error: ' + err.message);
  } finally {
    // Hide spinner in all cases
    document.getElementById('interpretLoadingSpinner').style.display = 'none';
  }
});

    async function fetchAndAppendResult(title, content, type) {
      if (usedTypes.has(type)) return;
      usedTypes.add(type);
      document.querySelectorAll(`#extraOptions .card[data-type="${type}"]`)
      .forEach(card => card.classList.add('card-disabled'));


      const spinner = document.getElementById('interpretLoadingSpinner');
      spinner.style.display = 'flex';

      try {
        const response = await fetch('/dreams/interpret', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ title, content, type })
        });

        if (!response.ok) throw new Error('Failed to interpret dream.');
        const data = await response.json();

        // Create result block and elements
        const resultBlock = document.createElement('div');
        resultBlock.classList.add('result-card'); // Add this instead of bg-gray etc.

                const heading = document.createElement('h3');
        heading.className = 'text-sm font-bold text-purple-300 mb-2';
        heading.textContent = `🔹 ${type.toUpperCase()}`;

        const resultPara = document.createElement('p');
        resultPara.className = 'whitespace-pre-wrap leading-relaxed text-white';

        // Typing animation logic
        let i = 0;
        const text = data.result;
        const speed = 15;

        function typeChar() {
          if (i < text.length) {
            resultPara.textContent += text.charAt(i);
            i++;
            setTimeout(typeChar, speed);
          }
        }
        typeChar();

        // Append result
        resultBlock.appendChild(heading);
        resultBlock.appendChild(resultPara);
        document.getElementById('allResults').appendChild(resultBlock);
        resultBlock.scrollIntoView({ behavior: 'smooth', block: 'start' });

        // Update hidden fields for saving
        document.getElementById('saveTitle').value = title.trim();
        const allTextBlocks = document.querySelectorAll('#allResults p');
        const fullInterpretation = [...allTextBlocks].map(p => p.textContent).join('\n\n');
        document.getElementById('saveContent').value = content.trim() + "\n\n" + fullInterpretation.trim();
        if (type === 'short') {
          document.getElementById('shortInterpretation').value = text.trim();
        }
        if (type === 'emotion') {
          document.getElementById('emotionSummary').value = text.trim();
        }


        formBox.classList.add('shift-left');
        resultBox.classList.add('slide-in');
      } catch (err) {
        alert('Error: ' + err.message);
      } finally {
        spinner.style.display = 'none';
      }
    }


document.querySelectorAll('#extraOptions .card').forEach(card => {
  card.addEventListener('click', () => {
    const type = card.dataset.type;
    const title = titleInput.value;
    const content = contentInput.value;
    fetchAndAppendResult(title, content, type);
  });
});


  document.addEventListener('click', (e) => {
  if (
    formBox.classList.contains('shift-left') &&
    !formBox.contains(e.target) &&
    !resultBox.contains(e.target)
  ) {
    goBack();
  }
});


const saveDreamForm = document.getElementById('saveDreamForm');

saveDreamForm.addEventListener('submit', async (e) => {
  e.preventDefault();

  const title = document.getElementById('saveTitle').value;
  const content = document.getElementById('saveContent').value;
  const short = document.getElementById('shortInterpretation').value;
  const emotion = document.getElementById('emotionSummary').value;

  document.getElementById('saveLoadingSpinner').style.display = 'flex';

  try {
    const response = await fetch(saveDreamForm.action, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        title,
        content,
        used_types: Array.from(usedTypes),
        short_interpretation: short || null,
        emotion_summary: emotion || null
      })
    });

    if (!response.ok) throw new Error('Failed to save dream.');

    const anim = document.getElementById('saveAnimation');
    anim.classList.remove('hidden');
    anim.style.opacity = 1;
    setTimeout(() => {
      anim.style.opacity = 0;
      setTimeout(() => anim.classList.add('hidden'), 400);
    }, 2500);

  } catch (err) {
    alert('Error: ' + err.message);
  } finally {
    document.getElementById('saveLoadingSpinner').style.display = 'none';
  }
});

</script>

<script>
  const canvas = document.getElementById("galaxyCanvas");
  const ctx = canvas.getContext("2d");
  let w = canvas.width = window.innerWidth;
  let h = canvas.height = window.innerHeight;
  const center = { x: w / 2, y: h / 2 };

  const stars = [];
  const starCount = 600;

  for (let i = 0; i < starCount; i++) {
    const radius = Math.sqrt(Math.random()) * (w / 2.2);
    const angle = Math.random() * Math.PI * 2;
    stars.push({
      radius,
      baseSize: Math.random() * 2.5 + 1,
      pulseSpeed: 0.005 + Math.random() * 0.01,
      angle,
      speed: 0.0005 + Math.random() * 0.0015,
      hue: Math.random() * 360,
      hueSpeed: 0.05 + Math.random() * 0.2,
      phase: Math.random() * Math.PI * 2
    });
  }

  function animate(time) {
    ctx.clearRect(0, 0, w, h);
    ctx.save();
    ctx.translate(center.x, center.y);

    for (let i = 0; i < stars.length; i++) {
      for (let j = i + 1; j < stars.length; j++) {
        const a = stars[i];
        const b = stars[j];
        const ax = a.radius * Math.cos(a.angle);
        const ay = a.radius * Math.sin(a.angle);
        const bx = b.radius * Math.cos(b.angle);
        const by = b.radius * Math.sin(b.angle);
        const dist = Math.hypot(ax - bx, ay - by);
        if (dist < 90) {
          ctx.beginPath();
          ctx.moveTo(ax, ay);
          ctx.lineTo(bx, by);
          ctx.strokeStyle = `rgba(255,255,255,${1 - dist / 90})`;
          ctx.lineWidth = 0.3;
          ctx.stroke();
        }
      }
    }

    for (let star of stars) {
      star.angle += star.speed;
      star.hue = (star.hue + star.hueSpeed) % 360;

      const x = star.radius * Math.cos(star.angle);
      const y = star.radius * Math.sin(star.angle);
      const size = star.baseSize + Math.sin(time * 0.002 + star.phase) * 0.6;

      ctx.beginPath();
      ctx.arc(x, y, size, 0, Math.PI * 2);
      const color = `hsla(${star.hue}, 100%, 70%, 0.8)`;
      ctx.fillStyle = color;
      ctx.shadowColor = color;
      ctx.shadowBlur = 10;
      ctx.fill();
    }

    ctx.restore();
    requestAnimationFrame(animate);
  }

  window.addEventListener("resize", () => {
    w = canvas.width = window.innerWidth;
    h = canvas.height = window.innerHeight;
    center.x = w / 2;
    center.y = h / 2;
  });

  animate();
</script>
</body>
</html>
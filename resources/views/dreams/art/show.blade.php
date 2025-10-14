<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Art Generator - {{ $dream->title }}</title>
  @vite('resources/css/app.css')
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    :root {
      --bg-deep: #0b1220;
      --bg-card: rgba(9,13,23,.6);
      --stroke: rgba(139,92,246,.25);
      --teal: #14b8a6;
      --cyan: #06b6d4;
      --violet: #8b5cf6;
      --text: #e2e8f0;
      --muted: #94a3b8;
    }

    * { box-sizing: border-box; }
    body {
      background: var(--bg-deep);
      color: var(--text);
      min-height: 100vh;
      overflow-x: hidden;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol", sans-serif;
    }

    /* Background */
    #vanta-bg { position: fixed; inset: 0; z-index: -2; }
    .bg-glow::before {
      content: "";
      position: fixed; inset: -10%;
      background:
        radial-gradient(60vw 60vw at 10% 20%, rgba(20,184,166,.14), transparent 55%),
        radial-gradient(70vw 70vw at 90% 10%, rgba(139,92,246,.12), transparent 60%);
      pointer-events: none; z-index: -1;
      animation: float 16s ease-in-out infinite alternate;
    }
    @keyframes float {
      from { transform: translateY(-1.5rem) } to { transform: translateY(1.5rem) }
    }

    /* Layout */
    .container { max-width: 1400px; margin: 0 auto; padding: 2rem 1rem; }
    h1, h2 { text-shadow: 0 6px 24px rgba(0,0,0,.4); }

    /* Buttons */
    .btn, .back-btn, .btn-secondary {
      display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
      padding: .7rem 1.2rem; border-radius: .8rem; font-weight: 600; cursor: pointer;
      transition: transform .2s, filter .2s, box-shadow .2s, background .2s, color .2s;
      text-decoration: none;
    }
    .btn, .back-btn {
      background: linear-gradient(135deg, var(--teal), var(--violet));
      color: #fff; border: 0; box-shadow: 0 8px 24px rgba(139,92,246,.32);
    }
    .btn:hover, .back-btn:hover { transform: translateY(-2px); filter: brightness(1.06); }
    .btn:disabled { opacity: .55; cursor: not-allowed; }

    .btn-secondary {
      background: rgba(139,92,246,.18);
      color: var(--text);
      border: 1px solid var(--stroke);
      box-shadow: 0 6px 18px rgba(0,0,0,.25);
    }
    .btn-secondary:hover { box-shadow: 0 10px 26px rgba(0,0,0,.35); }

    /* Cards */
    .card {
      background: var(--bg-card);
      border: 1px solid var(--stroke);
      border-radius: 1rem;
      padding: 1.5rem;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      box-shadow: 0 10px 30px rgba(0,0,0,.35);
    }

    /* Inputs */
    textarea, input[type="text"], input[type="file"] {
      width: 100%; padding: 1rem;
      background: rgba(2,6,23,.75);
      border: 1px solid var(--stroke);
      color: var(--text);
      border-radius: .8rem; outline: none;
      font-family: inherit; line-height: 1.6;
    }
    textarea { resize: vertical; }
    textarea:focus, input[type="text"]:focus {
      border-color: var(--cyan);
      box-shadow: 0 0 0 3px rgba(6,182,212,.18);
    }

    /* Prompt box */
    .prompt-box {
      background: rgba(2,6,23,.75);
      border: 1px solid var(--stroke);
      border-radius: .8rem;
      padding: 1rem; color: var(--text); line-height: 1.7; min-height: 120px;
    }

    /* Gallery */
    .gallery-grid {
      display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 1.5rem; margin-top: 2rem;
    }
    .art-card {
      background: var(--bg-card);
      border: 1px solid var(--stroke);
      border-radius: 1rem; overflow: hidden;
      transition: transform .25s, box-shadow .25s;
    }
    .art-card:hover { transform: translateY(-4px); box-shadow: 0 15px 40px rgba(139,92,246,.4); }
    .art-card img { width: 100%; height: 220px; object-fit: cover; }

    .art-card-content { padding: 1rem; }
    .art-card-title { font-size: 1.1rem; font-weight: 600; color: var(--cyan); margin-bottom: .5rem; }
    .art-card-prompt { font-size: .9rem; color: var(--muted); line-height: 1.5; }

    .meta-row { display:flex; align-items:center; justify-content:space-between; margin-top: .75rem; }
    .delete-btn {
      background: #ef4444; color: #fff; border: 0; border-radius: .6rem;
      padding: .5rem .9rem; font-size: .9rem; cursor: pointer; transition: background .2s;
    }
    .delete-btn:hover { background:#dc2626; }

    /* Empty */
    .empty-state { text-align:center; padding: 3rem 1rem; color: var(--muted); }
    .empty-icon { width: 80px; height: 80px; margin: 0 auto 1rem; opacity: .3; display:block; }

    /* Spinner */
    .spinner {
      border: 3px solid rgba(255,255,255,.3);
      border-radius: 50%;
      border-top: 3px solid white;
      width: 20px; height: 20px;
      animation: spin 1s linear infinite; display: inline-block;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>
</head>
<body class="bg-glow">
  <div id="vanta-bg"></div>

  <div class="container">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8" data-aos="fade-down">
      <a href="{{ route('imagine.portal') }}" class="back-btn">Dream Portal</a>
      <h1 class="text-3xl font-bold text-center flex-1">Dream Art Generator</h1>
      <div style="width:150px;"></div>
    </div>

    <!-- Generator -->
    <div class="card" data-aos="fade-up">
      <h2 class="text-2xl font-bold mb-4" style="color: var(--violet);">{{ $dream->title }}</h2>

      <div class="mb-6">
        <h3 class="text-lg font-semibold mb-2" style="color: var(--cyan);">Generate AI Art Prompt</h3>
        <p style="color: var(--muted); margin-bottom: 1rem;">
          Create an art-ready description from your dream. Use the prompt with image models such as DALL·E, Midjourney, or Stable Diffusion.
        </p>

        <button id="generatePromptBtn" class="btn" onclick="generatePrompt()">Generate Art Prompt</button>

        <div id="promptDisplay" class="prompt-box" style="display:none; margin-top:1rem;">
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.5rem;">
            <strong style="color: var(--cyan);">Generated Prompt</strong>
            <button type="button" onclick="copyPrompt(event)" class="btn-secondary" style="padding:.45rem .9rem; font-size:.9rem;">Copy</button>
          </div>
          <div id="promptText"></div>

          <div style="margin-top:1rem; padding:1rem; background: rgba(6,182,212,.1); border-left: 3px solid var(--cyan); border-radius:.6rem;">
            <strong style="color: var(--cyan); display:block; margin-bottom:.5rem;">Instructions</strong>
            <ol style="margin-left:1.2rem; color: var(--muted); font-size:.95rem; line-height:1.6;">
              <li>Copy the prompt above.</li>
              <li>Open an art generator:
                <ul style="margin:.3rem 0 0 1rem;">
                  <li><a href="https://openai.com/dall-e-3" target="_blank" style="color: var(--cyan); text-decoration: underline;">DALL·E 3</a></li>
                  <li><a href="https://www.midjourney.com" target="_blank" style="color: var(--cyan); text-decoration: underline;">Midjourney</a></li>
                  <li><a href="https://stablediffusionweb.com" target="_blank" style="color: var(--cyan); text-decoration: underline;">Stable Diffusion</a></li>
                </ul>
              </li>
              <li>Paste the prompt and generate your artwork.</li>
              <li>Download the image and upload it below to add it to your gallery.</li>
            </ol>
          </div>
        </div>

        <!-- Save form -->
        <div id="saveForm" style="display:none; margin-top:1.5rem;">
          <h3 class="text-lg font-semibold mb-3" style="color: var(--cyan);">Save Artwork</h3>

          <div class="mb-3">
            <label class="block mb-2 text-sm" style="color: var(--muted);">Title</label>
            <input type="text" id="artTitle" placeholder="Title" value="{{ $dream->title }} - Art">
          </div>

          <div class="mb-3">
            <label class="block mb-2 text-sm" style="color: var(--muted);">Description (optional)</label>
            <textarea id="artDescription" rows="3" placeholder="Add notes about this piece..."></textarea>
          </div>

          <div class="mb-3">
            <label class="block mb-2 text-sm" style="color: var(--muted);">Upload Generated Image</label>
            <input type="file" id="artImage" accept="image/*" style="color: var(--muted); padding:.8rem 1rem;">
            <p style="font-size:.85rem; color: var(--muted); margin-top:.35rem;">Upload the image you generated using the prompt above.</p>
          </div>

          <button class="btn" onclick="saveArt()">Save to Gallery</button>
        </div>
      </div>
    </div>

    <!-- Gallery -->
    <div class="mt-8" data-aos="fade-up" data-aos-delay="150">
      <h2 class="text-2xl font-bold mb-4" style="color: var(--teal);">Your Art Gallery</h2>

      @if($arts->count() > 0)
        <div class="gallery-grid">
          @foreach($arts as $art)
            <div class="art-card">
              @if($art->image_path)
                <img src="{{ asset('storage/' . $art->image_path) }}" alt="{{ $art->title }}">
              @else
                <div style="height:220px; background: linear-gradient(135deg, rgba(20,184,166,.25), rgba(139,92,246,.25)); display:flex; align-items:center; justify-content:center; color: var(--muted);">
                  <svg class="empty-icon" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm4 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM5.5 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm.5 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                    <path d="M16 8c0 3.15-1.866 2.585-3.567 2.07C11.42 9.763 10.465 9.473 10 10c-.603.683-.475 1.819-.351 2.92C9.826 14.495 9.996 16 8 16a8 8 0 1 1 8-8zm-8 7c.611 0 .654-.171.655-.176.078-.146.124-.464.07-1.119-.014-.168-.037-.37-.061-.591-.052-.464-.112-1.005-.118-1.462-.01-.707.083-1.61.704-2.314.369-.417.845-.578 1.272-.618.404-.038.812.026 1.16.104.343.077.702.186 1.025.284l.028.008c.346.105.658.199.953.266.653.148.904.083.991.024C14.717 9.38 15 9.161 15 8a7 7 0 1 0-7 7z"/>
                  </svg>
                </div>
              @endif

              <div class="art-card-content">
                <div class="art-card-title">{{ $art->title }}</div>
                <div class="art-card-prompt">{{ Str::limit($art->prompt, 100) }}</div>

                @if($art->description)
                  <p style="font-size:.9rem; color: var(--muted); margin-top:.5rem;">{{ $art->description }}</p>
                @endif

                <div class="meta-row">
                  <span style="font-size:.8rem; color: var(--muted);">{{ $art->created_at->diffForHumans() }}</span>
                  <form action="{{ route('dream.art.destroy', $art) }}" method="POST" onsubmit="return confirm('Delete this artwork?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">Delete</button>
                  </form>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="empty-state">
          <svg class="empty-icon" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm4 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM5.5 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm.5 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
          </svg>
          <h3 class="text-xl mb-2">No artwork yet</h3>
          <p>Generate your first prompt to get started.</p>
        </div>
      @endif
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    VANTA.NET({
      el: "#vanta-bg",
      mouseControls: true, touchControls: true, gyroControls: false,
      minHeight: 200.00, minWidth: 200.00, scale: 1.0, scaleMobile: 1.0,
      color: 0x8b5cf6, backgroundColor: 0x0b1220, points: 9.0, maxDistance: 22.0, spacing: 18.0
    });
    AOS.init({ once: true, duration: 800, easing: 'ease-out-cubic' });

    let generatedPrompt = '';

    async function generatePrompt() {
      const btn = document.getElementById('generatePromptBtn');
      const display = document.getElementById('promptDisplay');
      const promptText = document.getElementById('promptText');
      const saveForm = document.getElementById('saveForm');

      btn.disabled = true;
      btn.innerHTML = '<span class="spinner" aria-hidden="true"></span> Generating…';

      try {
        const response = await fetch('{{ route('dream.art.generate-prompt', $dream) }}', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        const data = await response.json();

        if (data.success) {
          generatedPrompt = data.prompt;
          promptText.textContent = data.prompt;
          display.style.display = 'block';
          saveForm.style.display = 'block';
        } else {
          alert('Failed to generate prompt: ' + (data.message || 'Unknown error'));
        }
      } catch (error) {
        alert('Error: ' + error.message);
      } finally {
        btn.disabled = false;
        btn.textContent = 'Generate Art Prompt';
      }
    }

    function copyPrompt(e) {
      if (!generatedPrompt) return;
      const btn = e?.target;

      if (navigator.clipboard?.writeText) {
        navigator.clipboard.writeText(generatedPrompt).then(() => {
          if (btn) {
            const original = btn.textContent;
            btn.textContent = 'Copied';
            btn.style.background = 'rgba(139,92,246,.32)';
            setTimeout(() => { btn.textContent = original; btn.style.background = ''; }, 1800);
          }
        }).catch(() => fallbackCopy(btn));
      } else {
        fallbackCopy(btn);
      }
    }

    function fallbackCopy(btn) {
      const t = document.createElement('textarea');
      t.value = generatedPrompt; t.style.position = 'fixed'; t.style.opacity = '0';
      document.body.appendChild(t); t.select(); t.setSelectionRange(0, 99999);
      try { document.execCommand('copy'); if (btn) { const o = btn.textContent; btn.textContent = 'Copied'; setTimeout(() => btn.textContent = o, 1800); } }
      catch { alert('Copy failed. Please select and copy manually.'); }
      finally { document.body.removeChild(t); }
    }

    async function saveArt() {
      const title = document.getElementById('artTitle').value.trim();
      const description = document.getElementById('artDescription').value.trim();
      const imageFile = document.getElementById('artImage').files[0];

      if (!title) { alert('Please enter a title.'); return; }
      if (!generatedPrompt) { alert('Please generate a prompt first.'); return; }

      const formData = new FormData();
      formData.append('title', title);
      formData.append('prompt', generatedPrompt);
      formData.append('description', description);
      if (imageFile) formData.append('image', imageFile);

      try {
        const response = await fetch('{{ route('dream.art.store', $dream) }}', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          body: formData
        });
        const data = await response.json();

        if (data.success) {
          // Keep UX snappy without emojis
          alert('Artwork saved.');
          location.reload();
        } else {
          alert('Failed to save: ' + (data.message || 'Unknown error'));
        }
      } catch (error) {
        alert('Error: ' + error.message);
      }
    }
  </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Art Generator — {{ $dream->title }}</title>
  @vite('resources/css/app.css')
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    :root{
      --bg:#0b1220; --card:rgba(9,13,23,.60); --stroke:rgba(139,92,246,.25);
      --teal:#14b8a6; --cyan:#06b6d4; --violet:#8b5cf6; --text:#e2e8f0; --muted:#94a3b8;
    }
    *{box-sizing:border-box}
    body{background:var(--bg); color:var(--text); min-height:100vh; font-family:Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, sans-serif; overflow-x:hidden}
    #vanta-bg{position:fixed; inset:0; z-index:-2}
    .bg-glow::before{content:""; position:fixed; inset:-10%;
      background:radial-gradient(60vw 60vw at 12% 18%, rgba(20,184,166,.14), transparent 55%),
                 radial-gradient(70vw 70vw at 85% 12%, rgba(139,92,246,.12), transparent 60%);
      pointer-events:none; z-index:-1; animation:float 16s ease-in-out infinite alternate}
    @keyframes float{from{transform:translateY(-1.2rem)} to{transform:translateY(1.2rem)}}
    .container{max-width:1400px; margin:0 auto; padding:2rem 1rem}
    h1,h2{ text-shadow:0 6px 24px rgba(0,0,0,.4) }

    /* buttons */
    .btn{display:inline-flex; align-items:center; justify-content:center; gap:.5rem; padding:.7rem 1.15rem;
      font-weight:600; border-radius:.85rem; border:0; color:#fff; cursor:pointer;
      background:linear-gradient(135deg, var(--teal), var(--violet));
      box-shadow:0 8px 24px rgba(139,92,246,.32); transition:transform .18s, filter .18s, box-shadow .18s}
    .btn:hover{transform:translateY(-1px); filter:brightness(1.06)}
    .btn:disabled{opacity:.55; cursor:not-allowed}
    .btn-ghost{background:rgba(139,92,246,.18); color:var(--text); border:1px solid var(--stroke); box-shadow:0 6px 18px rgba(0,0,0,.25)}
    .btn-ghost:hover{box-shadow:0 10px 26px rgba(0,0,0,.35)}
    .back{padding:.6rem 1rem; border-radius:.75rem; text-decoration:none}

    /* cards */
    .card{background:var(--card); border:1px solid var(--stroke); border-radius:1rem; box-shadow:0 10px 30px rgba(0,0,0,.35); backdrop-filter:blur(10px)}
    .card-pad{padding:1.5rem}

    /* inputs */
    textarea, input[type="text"]{
      width:100%; padding:1rem; background:rgba(2,6,23,.78); color:var(--text);
      border:1px solid var(--stroke); border-radius:.8rem; outline:none; line-height:1.6; font-family:inherit}
    textarea{resize:vertical; min-height:100px}
    textarea:focus, input[type="text"]:focus{border-color:var(--cyan); box-shadow:0 0 0 3px rgba(6,182,212,.18)}

    /* layout */
    .split{display:grid; grid-template-columns: 1fr 1fr; gap:2rem; align-items:start}
    @media (max-width: 1100px){ .split{grid-template-columns:1fr} }
    .section-title{font-size:1.05rem; font-weight:600; color:var(--cyan); margin-bottom:.75rem}
    .label{font-size:.9rem; color:var(--muted); margin-bottom:.4rem; display:block}

    /* preview */
    .preview-wrap{position:sticky; top:1.5rem}
    .preview-box{background:rgba(0,0,0,.5); border-radius:1rem; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.5); position:relative}
    .preview-aspect{aspect-ratio: 1 / 1; width:100%; display:grid; place-items:center; min-height:400px}
    .preview-aspect img{width:100%; height:100%; object-fit:cover; display:block}
    
    /* Blur reveal animation */
    .reveal-container{position:relative; overflow:hidden}
    .blur-overlay{
      position:absolute; top:0; left:0; width:100%; height:100%;
      backdrop-filter:blur(18px); z-index:2;
      animation:blurReveal 3.7s cubic-bezier(0.65, 0, 0.35, 1) forwards;
    }
    @keyframes blurReveal{
      0%{transform:translateY(0%); opacity:1}
      100%{transform:translateY(-100%); opacity:0}
    }

    /* gallery */
    .gallery{display:grid; grid-template-columns:repeat(auto-fill, minmax(280px,1fr)); gap:1.25rem}
    .card-art{border:1px solid var(--stroke); border-radius:1rem; overflow:hidden; transition:transform .18s, box-shadow .18s}
    .card-art:hover{transform:translateY(-2px); box-shadow:0 15px 40px rgba(139,92,246,.4)}
    .card-art img{width:100%; height:220px; object-fit:cover}
    .card-body{padding:1rem}
    .title{font-size:1.05rem; font-weight:600; color:var(--cyan)}
    .muted{color:var(--muted)}
    .row{display:flex; align-items:center; justify-content:space-between; gap:.75rem}
    .delete{background:#ef4444; color:#fff; border:0; border-radius:.6rem; padding:.45rem .85rem; font-size:.88rem; cursor:pointer}
    .delete:hover{background:#dc2626}

    /* status */
    .status{display:none; margin-top:1rem; padding:1rem 1.25rem; background:rgba(6,182,212,.10); border-left:4px solid var(--cyan); border-radius:.75rem}
    .spinner{border:3px solid rgba(255,255,255,.3); border-top:3px solid #fff; border-radius:50%; width:18px; height:18px; animation:spin 1s linear infinite; display:inline-block}
    @keyframes spin{to{transform:rotate(360deg)}}
  </style>
</head>
<body class="bg-glow">
  <div id="vanta-bg"></div>

  <div class="container">
    <!-- Header -->
    <div class="row" style="margin-bottom:1.5rem" data-aos="fade-down">
      <a href="{{ route('imagine.portal') }}" class="btn-ghost back">Dream Portal</a>
      <h1 class="text-3xl font-bold" style="margin:0 auto">Dream Art Generator</h1>
      <span style="width:120px"></span>
    </div>

    <!-- Main split -->
    <!-- Main split -->
    <div class="split" data-aos="fade-up">
      <!-- Left: Controls -->
      <div class="card card-pad">
        <h2 class="text-2xl font-bold" style="color:var(--violet); margin-bottom:.8rem">{{ $dream->title }}</h2>
        <p class="muted" style="margin-bottom:.5rem; font-size:.95rem">Create artwork from your dream</p>
        <p class="muted" style="margin-bottom:1.5rem; font-size:.85rem; padding:.5rem; background:rgba(139,92,246,.1); border-radius:.5rem; border:1px solid rgba(139,92,246,.2)">
          <strong>Note:</strong> Generated art is saved to this dream's gallery below. Each dream has its own art collection.
        </p>

        <!-- Generate Section -->
        <div style="margin-bottom:1.5rem">
        
          <button id="generateBtn" class="btn" onclick="generateImage()">Generate Image</button>
          
          <div id="status" class="status">
            <div style="display:flex; align-items:center; gap:.75rem">
              <div class="spinner"></div>
              <div>
                <strong style="color:var(--cyan)">Creating your image…</strong>
                <div class="muted" style="font-size:.9rem">This may take 30-60 seconds. Please be patient.</div>
              </div>
            </div>
          </div>
        </div>

        <hr style="border:0; border-top:1px solid var(--stroke); margin:1.5rem 0">

        <!-- Custom Prompt Section -->
        <div style="margin-bottom:1.5rem">
          <div class="section-title">Optional Custom Prompt</div>
          <p class="muted" style="font-size:.9rem; margin-bottom:.75rem">
            By default, your dream will be used. Optionally enter a custom description to generate a different image.
          </p>
          <textarea id="customPrompt" rows="4" placeholder="e.g., A surreal landscape with floating islands and purple skies..."></textarea>
        </div>

        <hr style="border:0; border-top:1px solid var(--stroke); margin:1.5rem 0">

        <!-- Save Section -->
        <div>
          <div class="section-title">Save to Gallery</div>
          <button id="saveBtn" class="btn" onclick="saveToGallery()" style="display:none">Save to Gallery</button>
          <p id="savedMessage" class="muted" style="font-size:.9rem; display:none; color:var(--teal)">✓ Saved to your gallery</p>
        </div>
      </div>

      <!-- Right: Preview -->
      <aside class="preview-wrap">
        <div class="card card-pad">
          <div class="section-title" style="margin-bottom:.75rem">Preview</div>

          <div class="preview-box reveal-container" id="previewContainer">
            <div class="preview-aspect">
              <img id="previewImg" alt="Generated Dream Art" style="display:none">
              <div id="placeholder" class="muted" style="font-size:.95rem">No image generated yet</div>
            </div>
          </div>
        </div>
      </aside>
    </div>    <!-- Gallery -->
    <section style="margin-top:2rem" data-aos="fade-up" data-aos-delay="120">
      <h2 class="text-2xl font-bold" style="color:var(--teal); margin-bottom:.75rem">Your Art Gallery</h2>

      @if($arts->count() > 0)
        <div id="gallery" class="gallery">
          @foreach($arts as $art)
            <article class="card-art" data-art-id="{{ $art->id }}">
              @if($art->image_path)
                <img loading="lazy" src="{{ asset('storage/'.$art->image_path) }}" alt="{{ $art->title }}">
              @endif
              <div class="card-body">
                <div class="title">{{ $art->title }}</div>
                <div class="muted" style="font-size:.9rem">{{ Str::limit($art->prompt, 120) }}</div>
                @if($art->description)
                  <div class="muted" style="font-size:.9rem; margin-top:.45rem">{{ $art->description }}</div>
                @endif
                <div class="row" style="margin-top:.7rem">
                  <span class="muted" style="font-size:.82rem">{{ $art->created_at->diffForHumans() }}</span>
                  <button type="button" class="delete" onclick="deleteArtwork({{ $art->id }}, this)">Delete</button>
                </div>
              </div>
            </article>
          @endforeach
        </div>
      @else
        <div id="gallery" class="gallery"></div>
        <div class="card card-pad muted" style="text-align:center; padding:2rem">
          <div style="font-size:3rem; margin-bottom:1rem; opacity:0.5">🎨</div>
          <p style="font-size:1.1rem; margin-bottom:0.5rem">No artwork yet for this dream</p>
          <p style="font-size:0.9rem; opacity:0.7">Generate your first image above to start building your art gallery!</p>
        </div>
      @endif
    </section>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    VANTA.NET({ el:"#vanta-bg", mouseControls:true, touchControls:true, gyroControls:false,
      minHeight:200, minWidth:200, scale:1.0, scaleMobile:1.0, color:0x8b5cf6, backgroundColor:0x0b1220, points:9.0, maxDistance:22.0, spacing:18.0 });
    AOS.init({ once:true, duration:800, easing:'ease-out-cubic' });

    // Elements
    const generateBtn = document.getElementById('generateBtn');
    const statusBox = document.getElementById('status');
    const imgEl = document.getElementById('previewImg');
    const placeholder = document.getElementById('placeholder');
    const previewContainer = document.getElementById('previewContainer');
    const saveBtn = document.getElementById('saveBtn');
    const savedMessage = document.getElementById('savedMessage');
    const customPromptInput = document.getElementById('customPrompt');

    let currentArtId = null;
    let currentImageUrl = null;

    // Generate Image
    async function generateImage() {
      generateBtn.disabled = true;
      generateBtn.innerHTML = '<span class="spinner"></span> Generating…';
      statusBox.style.display = 'block';
      saveBtn.style.display = 'none';
      savedMessage.style.display = 'none';

      try {
        // Get custom prompt if provided
        const customPrompt = customPromptInput.value.trim();
        
        const requestBody = customPrompt 
          ? { custom_prompt: customPrompt }
          : {};

        const res = await fetch('{{ route('dream.art.generate-image', $dream) }}', {
          method: 'POST',
          headers: { 
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
          },
          body: JSON.stringify(requestBody)
        });

        const data = await res.json();
        
        if (!data.success) throw new Error(data.message || 'Generation failed');

        // Verify art_id is returned
        if (!data.art_id) {
          console.error('No art_id in response:', data);
          throw new Error('Image generated but not saved properly. Please try again.');
        }

        console.log('Image generated successfully:', {
          art_id: data.art_id,
          image_url: data.image_url
        });

        // Store data
        currentArtId = data.art_id;
        currentImageUrl = data.image_url;

        // Show image with blur reveal animation
        placeholder.style.display = 'none';
        imgEl.src = data.image_url;
        imgEl.style.display = 'block';

        // Add blur overlay for reveal animation
        const existingOverlay = previewContainer.querySelector('.blur-overlay');
        if (existingOverlay) existingOverlay.remove();
        
        const blurOverlay = document.createElement('div');
        blurOverlay.className = 'blur-overlay';
        previewContainer.appendChild(blurOverlay);

        // Show save button
        saveBtn.style.display = 'inline-flex';

      } catch (err) {
        alert('Error: ' + err.message);
      } finally {
        statusBox.style.display = 'none';
        generateBtn.disabled = false;
        generateBtn.textContent = 'Generate Image';
      }
    }

    // Save to Gallery
    async function saveToGallery() {
      if (!currentArtId) {
        alert('No image to save. Please generate an image first.');
        return;
      }

      saveBtn.disabled = true;
      saveBtn.innerHTML = '<span class="spinner"></span> Saving…';

      try {
        // Verify the image is saved by checking with backend
        const verifyRes = await fetch(`/dream-art/${currentArtId}/verify`, {
          method: 'GET',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        });

        if (!verifyRes.ok) {
          throw new Error('Failed to verify saved image. Please try generating again.');
        }

        const verifyData = await verifyRes.json();
        if (!verifyData.exists) {
          throw new Error('Image was not properly saved. Please try generating again.');
        }

        // Image is already saved on backend, just show confirmation and reload
        saveBtn.innerHTML = '✓ Saved!';
        saveBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        savedMessage.style.display = 'block';
        
        setTimeout(() => {
          location.reload();
        }, 1500);
      } catch (err) {
        alert('Error: ' + err.message);
        saveBtn.disabled = false;
        saveBtn.innerHTML = 'Save to Gallery';
      }
    }

    // Delete Artwork without page reload
    async function deleteArtwork(artId, buttonElement) {
      if (!confirm('Delete this artwork?')) return;

      const card = buttonElement.closest('.card-art');
      const originalHTML = buttonElement.innerHTML;
      
      buttonElement.disabled = true;
      buttonElement.innerHTML = '<span class="spinner"></span>';

      try {
        const res = await fetch(`/dream-art/${artId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        });

        const data = await res.json();

        if (!data.success) throw new Error(data.message || 'Delete failed');

        // Fade out and remove the card
        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.9)';
        
        setTimeout(() => {
          card.remove();
          
          // Check if gallery is empty
          const gallery = document.getElementById('gallery');
          if (gallery.children.length === 0) {
            gallery.innerHTML = '<div class="card card-pad muted" style="text-align:center; grid-column: 1/-1">No artwork yet.</div>';
          }
        }, 300);

      } catch (err) {
        alert('Error: ' + err.message);
        buttonElement.disabled = false;
        buttonElement.innerHTML = originalHTML;
      }
    }
  </script>
</body>
</html>

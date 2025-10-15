<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Art Generator - {{ $dream->title }}</title>
  @vite('resources/css/app.css')
  <style>
    html, body {
      margin: 0;
      padding: 0;
      min-height: 100%;
      font-family: 'Inter', sans-serif;
      background: black;
      color: white;
    }

    .content {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 3rem 1.5rem;
    }

    .home-btn {
      position: fixed;
      top: 1.5rem;
      left: 1.5rem;
      background: linear-gradient(135deg, #14b8a6, #ec4899);
      padding: 0.6rem 1.4rem;
      font-size: 0.9rem;
      color: white;
      font-weight: 600;
      border-radius: 0.6rem;
      text-decoration: none;
      z-index: 10;
      box-shadow: 0 0 12px rgba(255,255,255,0.1);
      transition: all 0.2s ease;
    }

    .home-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 16px rgba(255,255,255,0.15);
    }

    .page-title {
      font-size: 1.875rem;
      font-weight: bold;
      margin-bottom: 1rem;
      text-align: center;
      color: #22d3ee;
      text-shadow: 0 0 1px #0ea5e9, 0 0 3px rgba(14, 165, 233, 0.4);
    }

    .dream-title {
      font-size: 1.3rem;
      font-weight: 600;
      color: #a78bfa;
      text-align: center;
      margin-bottom: 1.5rem;
      text-shadow: 0 0 2px #8b5cf6;
    }

    .art-img-wrapper {
      width: 100%;
      max-width: 700px;
      margin: 2rem auto;
      background-color: rgba(255, 255, 255, 0.02);
      border-radius: 1.5rem;
      padding: 1rem;
      box-shadow: 0 0 20px rgba(255, 255, 255, 0.06);
      cursor: pointer;
      transition: transform 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .art-img-wrapper:hover {
      transform: scale(1.02);
    }

    .art-image {
      width: 100%;
      height: auto;
      object-fit: cover;
      border-radius: 1rem;
      border: none;
      display: block;
      position: relative;
      z-index: 1;
    }

    /* Beautiful Reveal Animation (from avatar page) */
    .reveal-container {
      position: relative;
      overflow: hidden;
    }

    .blur-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      backdrop-filter: blur(18px);
      animation: blurReveal 3.7s cubic-bezier(0.65, 0, 0.35, 1) forwards;
      z-index: 2;
    }

    @keyframes blurReveal {
      0% {
        transform: translateY(0%);
        opacity: 1;
      }
      100% {
        transform: translateY(-100%);
        opacity: 0;
      }
    }

    .generate-btn {
      margin: 1rem 0.5rem;
      background: rgba(255, 255, 255, 0.05);
      color: #e2e8f0;
      padding: 0.6rem 1.4rem;
      font-size: 0.9rem;
      border-radius: 0.6rem;
      font-weight: 600;
      border: 1px solid rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(6px);
      transition: all 0.2s ease;
      text-shadow: 0 0 2px #14b8a6, 0 0 4px rgba(20, 184, 166, 0.6);
      cursor: pointer;
    }

    .generate-btn:hover {
      background: rgba(255, 255, 255, 0.12);
      box-shadow: 0 0 12px rgba(255, 255, 255, 0.1);
      transform: translateY(-2px);
    }

    .generate-btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .save-btn {
      margin: 1.5rem 0.5rem;
      background: linear-gradient(135deg, #14b8a6, #8b5cf6);
      color: white;
      padding: 0.7rem 1.8rem;
      font-size: 0.95rem;
      border-radius: 0.6rem;
      font-weight: 600;
      border: none;
      transition: all 0.2s ease;
      text-shadow: 0 0 2px rgba(0,0,0,0.3);
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(20, 184, 166, 0.4);
    }

    .save-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(20, 184, 166, 0.5);
    }

    .save-btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .meta-text {
      color: #cbd5e1;
      font-size: 0.95rem;
      font-weight: 500;
      margin: 0.5rem 0;
      text-align: center;
      line-height: 1.6;
    }

    .status-box {
      max-width: 600px;
      margin: 1.5rem auto;
      padding: 1.5rem;
      background: rgba(6, 182, 212, 0.1);
      border-left: 4px solid #06b6d4;
      border-radius: 0.8rem;
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .prompt-box {
      max-width: 700px;
      margin: 1.5rem auto;
      padding: 1.5rem;
      background: rgba(139, 92, 246, 0.1);
      border-left: 4px solid #8b5cf6;
      border-radius: 0.8rem;
    }

    .prompt-label {
      color: #a78bfa;
      font-weight: 600;
      display: block;
      margin-bottom: 0.5rem;
      font-size: 1rem;
    }

    .spinner {
      border: 3px solid rgba(255,255,255,.3);
      border-radius: 50%;
      border-top: 3px solid white;
      width: 24px;
      height: 24px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .saved-arts-title {
      margin-top: 3rem;
      font-size: 1.3rem;
      font-weight: bold;
      color: #22d3ee;
      text-align: center;
      text-shadow: 0 0 2px #0ea5e9;
    }

    .toggle-btn {
      margin: 1rem 0.5rem;
      background: rgba(255, 255, 255, 0.05);
      color: #e2e8f0;
      padding: 0.6rem 1.4rem;
      font-size: 0.9rem;
      border-radius: 0.6rem;
      font-weight: 600;
      border: 1px solid rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(6px);
      transition: all 0.2s ease;
      text-shadow: 0 0 2px #14b8a6, 0 0 4px rgba(20, 184, 166, 0.6);
      cursor: pointer;
    }

    .toggle-btn:hover {
      background: rgba(255, 255, 255, 0.12);
      box-shadow: 0 0 12px rgba(255, 255, 255, 0.1);
      transform: translateY(-2px);
    }

    .art-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-top: 1.5rem;
      max-width: 1200px;
      margin-left: auto;
      margin-right: auto;
    }

    .saved-art-card {
      background: rgba(30, 41, 59, 0.7);
      padding: 1rem;
      border-radius: 0.75rem;
      transition: transform 0.3s;
      backdrop-filter: blur(6px);
      border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .saved-art-card:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 24px rgba(139, 92, 246, 0.3);
    }

    .saved-art-img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 0.5rem;
      margin-bottom: 0.75rem;
      cursor: pointer;
    }

    .saved-meta {
      font-size: 0.85rem;
      color: #94a3b8;
      margin-bottom: 0.3rem;
    }

    .saved-title {
      font-size: 1rem;
      font-weight: 600;
      color: #22d3ee;
      margin-bottom: 0.5rem;
    }

    .delete-btn {
      background: #ef4444;
      color: white;
      border: none;
      padding: 0.4rem 0.9rem;
      border-radius: 0.5rem;
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
      margin-top: 0.5rem;
    }

    .delete-btn:hover {
      background: #dc2626;
    }

    .empty-state {
      text-align: center;
      padding: 3rem 1rem;
      color: #94a3b8;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 50;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.85);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      position: relative;
      max-width: 90%;
      max-height: 90%;
      border-radius: 1rem;
    }

    .modal-content img {
      width: 100%;
      height: auto;
      border-radius: 1rem;
    }

    .download-btn {
      position: absolute;
      bottom: 1rem;
      right: 1rem;
      background: #14b8a6;
      color: white;
      padding: 0.5rem 1rem;
      font-size: 0.85rem;
      border-radius: 0.5rem;
      font-weight: bold;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="content">
    <a href="{{ route('imagine.portal') }}" class="home-btn">🌌 Dream Portal</a>

    <h1 class="page-title">Dream Art Generator</h1>
    <h2 class="dream-title">{{ $dream->title }}</h2>

    <p class="meta-text" style="max-width: 600px; margin: 0 auto 2rem;">
      Generate a unique AI-powered artwork directly from your dream using DALL-E 3. 
      The system will analyze your dream content and create a stunning visual representation.
    </p>

    <!-- Generate Button -->
    <button id="generateImageBtn" class="generate-btn" onclick="generateImage()">
      Generate Dream Art
    </button>

    <!-- Loading Status -->
    <div id="generatingStatus" class="status-box" style="display: none;">
      <div class="spinner"></div>
      <div>
        <strong style="color: #06b6d4; display: block; margin-bottom: 0.3rem;">Creating Your Dream Art...</strong>
        <p style="color: #94a3b8; font-size: 0.9rem; margin: 0;">This may take 10-30 seconds. Please wait.</p>
      </div>
    </div>

    <!-- Generated Image Display -->
    <div id="imageDisplay" style="display: none;">
      <div class="art-img-wrapper reveal-container" onclick="openModal(document.getElementById('generatedImage').src)">
        <img id="generatedImage" src="" alt="Generated Dream Art" class="art-image">
        <div class="blur-overlay"></div>
      </div>

      <div class="prompt-box">
        <strong class="prompt-label">Artistic Prompt Used:</strong>
        <p id="usedPrompt" class="meta-text" style="text-align: left;"></p>
      </div>

      <button id="saveImageBtn" class="save-btn" onclick="saveGeneratedImage()">
        💾 Save to Gallery
      </button>
    </div>

    <!-- Saved Arts Section -->
    @if($arts->count() > 0)
      <button class="toggle-btn" onclick="toggleSavedArts()">View Saved Artworks ({{ $arts->count() }})</button>

      <div id="savedArtsSection" style="display: none; width: 100%;">
        <h2 class="saved-arts-title">Your Art Gallery</h2>
        <div class="art-grid">
          @foreach($arts as $art)
            <div class="saved-art-card">
              @if($art->image_path)
                <img src="{{ asset('storage/' . $art->image_path) }}" 
                     class="saved-art-img" 
                     alt="{{ $art->title }}"
                     onclick="openModal('{{ asset('storage/' . $art->image_path) }}')">
              @else
                <div style="height: 200px; background: linear-gradient(135deg, rgba(20,184,166,.25), rgba(139,92,246,.25)); display: flex; align-items: center; justify-content: center; border-radius: 0.5rem; margin-bottom: 0.75rem;">
                  <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16" style="opacity: 0.3;">
                    <path d="M8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm4 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM5.5 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm.5 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                  </svg>
                </div>
              @endif

              <div class="saved-title">{{ $art->title }}</div>
              <div class="saved-meta">{{ Str::limit($art->prompt, 80) }}</div>
              @if($art->description)
                <div class="saved-meta" style="margin-top: 0.3rem;">{{ $art->description }}</div>
              @endif
              <div class="saved-meta" style="margin-top: 0.5rem;">{{ $art->created_at->diffForHumans() }}</div>
              
              <form action="{{ route('dream.art.destroy', $art) }}" method="POST" onsubmit="return confirm('Delete this artwork?');" style="margin-top: 0.5rem;">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-btn">Delete</button>
              </form>
            </div>
          @endforeach
        </div>
      </div>
    @else
      <div class="empty-state" style="margin-top: 3rem;">
        <svg width="80" height="80" fill="currentColor" viewBox="0 0 16 16" style="opacity: 0.3; margin: 0 auto 1rem; display: block;">
          <path d="M8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm4 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM5.5 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm.5 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
        </svg>
        <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">No artwork yet</h3>
        <p>Generate your first dream artwork to get started</p>
      </div>
    @endif
  </div>

  <!-- Modal -->
  <div id="artModal" class="modal" onclick="closeModal(event)">
    <div class="modal-content">
      <img id="modalImg" src="" alt="Zoomed Art">
      <a id="downloadLink" download>
        <button class="download-btn">⬇ Download</button>
      </a>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    let currentImagePath = '';
    let currentPrompt = '';
    let currentArtId = null;

    function toggleSavedArts() {
      const section = document.getElementById("savedArtsSection");
      section.style.display = section.style.display === "none" ? "block" : "none";
    }

    function openModal(imageSrc) {
      const modal = document.getElementById('artModal');
      const modalImg = document.getElementById('modalImg');
      const downloadLink = document.getElementById('downloadLink');
      modal.style.display = 'flex';
      modalImg.src = imageSrc;
      downloadLink.href = imageSrc;
    }

    function closeModal(event) {
      if (event.target.id === 'artModal') {
        document.getElementById('artModal').style.display = 'none';
      }
    }

    async function generateImage() {
      const btn = document.getElementById('generateImageBtn');
      const status = document.getElementById('generatingStatus');
      const display = document.getElementById('imageDisplay');

      btn.disabled = true;
      btn.innerHTML = '<span class="spinner"></span> Generating...';
      status.style.display = 'flex';
      display.style.display = 'none';

      try {
        const response = await fetch('{{ route('dream.art.generate-image', $dream) }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        });

        const data = await response.json();

        if (data.success) {
          const imgElement = document.getElementById('generatedImage');
          imgElement.src = data.image_url;
          document.getElementById('usedPrompt').textContent = data.prompt;
          
          // Store data for saving later
          currentImagePath = data.image_url;
          currentPrompt = data.prompt;
          currentArtId = data.art_id;
          
          status.style.display = 'none';
          display.style.display = 'block';
          
          // Trigger blur reveal animation by re-adding the blur overlay
          const wrapper = document.querySelector('.reveal-container');
          const existingOverlay = wrapper.querySelector('.blur-overlay');
          if (existingOverlay) {
            existingOverlay.remove();
          }
          const newOverlay = document.createElement('div');
          newOverlay.className = 'blur-overlay';
          wrapper.appendChild(newOverlay);
        } else {
          alert('Failed to generate image: ' + (data.message || 'Unknown error'));
          status.style.display = 'none';
        }
      } catch (error) {
        alert('Error: ' + error.message);
        status.style.display = 'none';
      } finally {
        btn.disabled = false;
        btn.innerHTML = 'Generate Dream Art';
      }
    }

    async function saveGeneratedImage() {
      const saveBtn = document.getElementById('saveImageBtn');
      
      if (!currentArtId) {
        alert('No image to save. Please generate an image first.');
        return;
      }

      saveBtn.disabled = true;
      saveBtn.innerHTML = '<span class="spinner"></span> Saving...';

      try {
        // The image is already saved in the backend, we just need to confirm
        // Show success message and reload to update gallery
        saveBtn.innerHTML = '✓ Saved!';
        saveBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        
        setTimeout(() => {
          location.reload();
        }, 1500);
      } catch (error) {
        alert('Error saving: ' + error.message);
        saveBtn.disabled = false;
        saveBtn.innerHTML = '💾 Save to Gallery';
      }
    }
  </script>
</body>
</html>

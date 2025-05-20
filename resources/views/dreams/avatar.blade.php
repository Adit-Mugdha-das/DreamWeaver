<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Avatar</title>
  @vite('resources/css/app.css')
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Inter', sans-serif;
      background: black;
      color: white;
    }

    .content {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      padding: 3rem 1.5rem;
    }

    .home-btn {
      position: fixed;
      top: 1.5rem;
      left: 1.5rem;
      background: linear-gradient(135deg, #14b8a6, #ec4899);
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
      color: white;
      font-weight: 600;
      border-radius: 0.5rem;
      text-decoration: none;
      z-index: 10;
      box-shadow: 0 0 10px rgba(255,255,255,0.1);
    }

    .avatar-img-wrapper {
  width: 560px;
  height: 560px;
  margin: 2rem auto 0.5rem;
  background-color: rgba(255, 255, 255, 0.02);
  border-radius: 1.5rem;
  padding: 1rem;
  box-shadow: 0 0 20px rgba(255, 255, 255, 0.06);
  cursor: pointer;
  transition: transform 0.3s ease;
}


    .avatar-img-wrapper:hover {
      transform: scale(1.02);
    }

    .avatar-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 1rem;
      border: none;
    }

    .meta-text {
      color: #cbd5e1;
      font-size: 0.95rem;
      font-weight: 500;
      margin: 0.25rem 0;
    }

    .generate-btn, .toggle-btn {
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
}

.generate-btn:hover, .toggle-btn:hover {
  background: rgba(255, 255, 255, 0.12);
  box-shadow: 0 0 12px rgba(255, 255, 255, 0.1);
  transform: translateY(-2px);
}


  .page-title {
  font-size: 1.875rem;
  font-weight: bold;
  margin-bottom: 1rem;
  text-align: center;
  color: #22d3ee; /* deep cyan (Tailwind cyan-400) */
  text-shadow: 0 0 1px #0ea5e9, 0 0 3px rgba(14, 165, 233, 0.4); /* soft neon cyan glow */
}





    /* Modal Styling */
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
      max-width: 600px;
      width: 90%;
      border-radius: 1rem;
    }

    .modal-content img {
      width: 100%;
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

    .saved-avatars-title {
      margin-top: 2rem;
      font-size: 1.2rem;
      font-weight: bold;
      color: #38bdf8;
    }

    .avatar-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
      gap: 1rem;
      margin-top: 1.5rem;
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
    }

    .saved-avatar-card {
      background: rgba(30, 41, 59, 0.7);
      padding: 1rem;
      border-radius: 0.75rem;
      transition: transform 0.3s;
      backdrop-filter: blur(6px);
    }

    .saved-avatar-card:hover {
      transform: scale(1.05);
    }

    .saved-avatar-img {
      width: 100%;
      border-radius: 9999px;
      border: none;
      margin-bottom: 0.5rem;
    }

    .saved-meta {
      font-size: 0.8rem;
      color: #94a3b8;
    }

.btn-glassy {
  display: inline-block;
  margin: 1rem 0.5rem;
  background: rgba(255, 255, 255, 0.05);
  color: #e2e8f0;
  padding: 0.6rem 1.4rem;
  font-size: 0.9rem;
  border-radius: 0.6rem;
  font-weight: 600;
  border: 1px solid rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(6px);
  text-decoration: none;
  transition: all 0.2s ease;
  text-shadow: 0 0 1.5px #14b8a6, 0 0 3px rgba(20, 184, 166, 0.4);
  text-align: center;
}

.btn-glassy:hover {
  background: rgba(255, 255, 255, 0.12);
  box-shadow: 0 0 12px rgba(255, 255, 255, 0.1);
  transform: translateY(-2px);
}
.btn-fixed {
  position: fixed;
  top: 1.5rem;
  left: 1.5rem;
  z-index: 10;
}
.page-fade-in {
  opacity: 0;
  transform: translateY(30px);
  animation: fadeInUp 0.8s ease-out forwards;
  animation-delay: 0.3s; /* Increase this value to delay more */
}


@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(30px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

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

.avatar-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 1rem;
  border: none;
  position: relative;
  z-index: 1;
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

 

  </style>
</head>
<body>

<div class="content page-fade-in">

  @php
    $imageMap = [
        'wings' => 'wings.png',
        'mask' => 'mask.png',
        'cloud' => 'cloud.png',
        'fire' => 'fire.png',
        'swirl' => 'swirl.png',
        'mirror' => 'mirror.png',
        'default' => 'unknown.png'
    ];
  @endphp

  <a href="{{ route('imagine.portal') }}" class="btn-glassy btn-fixed">🌌 Dream Portal</a>



  <h1 class="page-title"> Your Dream Avatar</h1>

  


  @if(session('message'))
    <div class="text-green-400 font-semibold mb-3 text-sm">
      {{ session('message') }}
    </div>
  @endif

  @if($avatar)
    @php $image = $imageMap[$avatar['item']] ?? $imageMap['default']; @endphp
    <div class="avatar-img-wrapper reveal-container">
  <img src="{{ asset('avatar/' . $image) }}" alt="{{ $avatar['item'] }}" class="avatar-image">
  <div class="blur-overlay"></div>
</div>

    <p class="meta-text">Color: {{ ucfirst($avatar['color']) }}</p>
    <p class="meta-text">Item: {{ ucfirst($avatar['item']) }}</p>
  @else
    <p class="text-gray-400 text-sm">No avatar generated yet.</p>
  @endif

  <form method="POST" action="{{ url('/avatar/generate') }}">
    @csrf
    <button class="generate-btn"> Generate Avatar From Dream Emotion</button>
  </form>

  @if(isset($savedAvatars) && count($savedAvatars) > 0)
    <button class="toggle-btn" onclick="toggleSavedAvatars()"> View Saved Avatars</button>

    <div id="savedAvatarsSection" style="display: none;">
      <h2 class="saved-avatars-title"> Saved Avatars</h2>
      <div class="avatar-grid">
        @foreach($savedAvatars as $saved)
          @php $savedImg = $imageMap[$saved['item']] ?? $imageMap['default']; @endphp
          <div class="saved-avatar-card">
            <div onclick="openModal('{{ asset('avatar/' . $savedImg) }}')" style="cursor: pointer;">
  <img src="{{ asset('avatar/' . $savedImg) }}" class="saved-avatar-img" alt="Saved Avatar">
</div>

            <div class="saved-meta">Color: {{ ucfirst($saved['color']) }}</div>
            <div class="saved-meta">Item: {{ ucfirst($saved['item']) }}</div>
          </div>
        @endforeach
      </div>
    </div>
  @endif
</div>

<!-- Modal -->
<div id="avatarModal" class="modal" onclick="closeModal(event)">
  <div class="modal-content">
    <img id="modalImg" src="" alt="Zoomed Avatar">
    <a id="downloadLink" download>
      <button class="download-btn">⬇ Download</button>
    </a>
  </div>
</div>

<script>
  function toggleSavedAvatars() {
    const section = document.getElementById("savedAvatarsSection");
    section.style.display = section.style.display === "none" ? "block" : "none";
  }

  function openModal(imageSrc) {
    const modal = document.getElementById('avatarModal');
    const modalImg = document.getElementById('modalImg');
    const downloadLink = document.getElementById('downloadLink');
    modal.style.display = 'flex';
    modalImg.src = imageSrc;
    downloadLink.href = imageSrc;
  }

  function closeModal(event) {
    if (event.target.id === 'avatarModal') {
      document.getElementById('avatarModal').style.display = 'none';
    }
  }
</script>

</body>
</html>

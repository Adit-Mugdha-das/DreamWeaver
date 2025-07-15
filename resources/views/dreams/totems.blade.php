<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Totems</title>

  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- AOS CSS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <style>
    body {
      background: radial-gradient(circle at top, #1e1b4b, #0f172a 70%);
      font-family: 'Inter', sans-serif;
      color: white;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }

    .glow {
      background: rgba(30, 27, 75, 0.6);
      backdrop-filter: blur(6px);
      border: 1px solid rgba(167, 139, 250, 0.3);
      box-shadow: 0 0 15px rgba(167, 139, 250, 0.6);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .glow:hover {
      transform: scale(1.08);
      box-shadow: 0 0 25px rgba(236, 72, 153, 0.8);
    }

    .tooltip {
      position: absolute;
      bottom: 110%;
      left: 50%;
      transform: translateX(-50%);
      background-color: #334155;
      color: #c4b5fd;
      padding: 0.3rem 0.5rem;
      border-radius: 0.25rem;
      font-size: 0.75rem;
      white-space: nowrap;
      opacity: 0;
      transition: opacity 0.3s ease;
      pointer-events: none;
      z-index: 10;
    }

    .totem:hover .tooltip {
      opacity: 1;
    }

    #particles-bg {
      position: fixed;
      width: 100%;
      height: 100%;
      z-index: -1;
    }

    .modal {
      backdrop-filter: blur(6px);
    }
  </style>
</head>
<body>
  <div id="particles-bg"></div>

  <!-- Back to Portal Button -->
  <a href="{{ url('/imagine') }}" 
     class="fixed top-4 left-4 z-20 bg-gradient-to-r from-purple-600 to-indigo-500 text-white px-4 py-2 rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
     ⬅ Dream World
  </a>

  <div class="min-h-screen flex flex-col items-center justify-center text-center px-6 py-12 relative z-10">
    <h1 class="text-3xl font-bold text-purple-300 mb-8"> Your Dream Totems</h1>

    @php
      $meanings = [
        'mirror' => ['🪞 Reflection & self-awareness', 'You looked into a mirror and saw your younger self.'],
        'wings' => ['🪽 Freedom or ambition', 'You flew above mountains, free from all fears.'],
        'fire' => ['🔥 Transformation or passion', 'You stood in a burning house but felt no pain.'],
        'mask' => ['🎭 Hidden emotions or identity', 'You wore a mask in a crowded room and no one noticed.'],
      ];
    @endphp

    @if(isset($tokens) && count($tokens))
      <div class="flex flex-wrap justify-center gap-8">
        @foreach($tokens as $token)
          <div 
            onclick="openModal(
              '{{ ucfirst($token) }}',
              '{{ $meanings[strtolower($token)][0] ?? 'Symbolic meaning' }}',
              `{{ $dreamSnippets[strtolower($token)] ?? 'No dream found.' }}`)"
            class="totem glow relative w-48 h-48 px-6 py-4 rounded-xl cursor-pointer flex flex-col items-center justify-center"
            data-aos="zoom-in" data-aos-delay="100"
          >
            <div class="tooltip">{{ $meanings[strtolower($token)][0] ?? '🌙 Symbolic dream totem' }}</div>
            <img src="/images/totems/{{ strtolower($token) }}.png" 
                 alt="{{ $token }}" 
                 class="w-24 h-24 mb-2 rounded-md hover:scale-110 transition"
                 onclick="event.stopPropagation(); showImageModal(this.src)">
            <span class="mt-1 text-lg">{{ ucfirst($token) }}</span>
          </div>
        @endforeach
      </div>
    @else
      <p class="text-gray-400 mt-4">You haven't collected any tokens yet. Submit a dream to begin!</p>
    @endif
  </div>

  <!-- Textual Modal -->
  <div id="totemModal" class="modal hidden fixed inset-0 z-50 bg-black/70 flex items-center justify-center"
       onclick="handleTotemBackgroundClick(event)">
    <div class="bg-gray-900 text-white p-6 rounded-xl w-11/12 max-w-md shadow-xl border border-purple-400/40 relative">
      <button onclick="closeModal()" class="absolute top-3 right-4 text-xl text-gray-400 hover:text-red-400">&times;</button>
      <h2 id="modalTitle" class="text-2xl font-bold text-purple-300 mb-4">Totem Title</h2>
      <p id="modalMeaning" class="text-sm text-purple-200 mb-3"></p>
      <p id="modalDream" class="text-base text-white italic"></p>

      <div class="mt-4 text-center">
        <button onclick="loadMatchingDreams()" 
                class="text-sm text-indigo-400 underline hover:text-pink-300 transition">
          🔍 View More Dreams Like This
        </button>
        <div id="moreDreamsResult" class="mt-4"></div>
      </div>
    </div>
  </div>

  <!-- Image Zoom Modal -->
  <div id="imageZoomModal" 
       class="hidden fixed inset-0 z-50 bg-black/90 flex items-center justify-center"
       onclick="handleBackgroundClick(event)">
    <img id="zoomedImage" src="" class="max-w-full max-h-full rounded-xl shadow-lg border border-purple-400">
    <button onclick="closeImageModal()" 
            class="absolute top-4 right-6 text-white text-3xl hover:text-red-400">&times;</button>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.11.0/tsparticles.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init();

    let currentEmotion = "";

    function openModal(title, meaning, dream) {
      document.getElementById('modalTitle').innerText = title;
      document.getElementById('modalMeaning').innerText = meaning;
      document.getElementById('modalDream').innerText = dream;
      document.getElementById('moreDreamsResult').innerHTML = "";
      currentEmotion = title.toLowerCase();
      document.getElementById('totemModal').classList.remove('hidden');
    }

    function loadMatchingDreams() {
      fetch(`/dreams/emotion/${currentEmotion}`)
        .then(res => res.json())
        .then(data => {
          document.getElementById('moreDreamsResult').innerHTML = data.html;
        })
        .catch(err => {
          document.getElementById('moreDreamsResult').innerHTML = '<p class="text-red-400">Error loading dreams.</p>';
        });
    }

    function closeModal() {
      document.getElementById('totemModal').classList.add('hidden');
    }

    function showImageModal(src) {
      document.getElementById('zoomedImage').src = src;
      document.getElementById('imageZoomModal').classList.remove('hidden');
    }

    function closeImageModal() {
      document.getElementById('imageZoomModal').classList.add('hidden');
    }

    function handleBackgroundClick(event) {
      const image = document.getElementById('zoomedImage');
      if (!image.contains(event.target)) {
        closeImageModal();
      }
    }

    function handleTotemBackgroundClick(event) {
      const modalContent = document.querySelector('#totemModal > div');
      if (!modalContent.contains(event.target)) {
        closeModal();
      }
    }
  </script>
</body>
</html>

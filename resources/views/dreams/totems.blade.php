<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Totems</title>

  <!-- Tailwind (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- AOS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <!-- Mythic serif for headings -->
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg-top:#0b1220;
      --bg-bottom:#0e1422;
      --glass:rgba(14,20,34,.72);
      --border:rgba(255,255,255,.10);
      --muted:#98a2b3;
      --ink:#eaf1f8;
      --gold:#d9c28f;
      --blue:#90b4ff;
      --teal:#6fb9c7;
    }

    *{box-sizing:border-box}
    body{
      margin:0; color:var(--ink); font-family:Inter,system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;
      min-height:100vh; overflow-x:hidden;
      background:
        radial-gradient(1000px 520px at 12% -10%, rgba(144,180,255,.10), transparent 58%),
        radial-gradient(880px 500px at 88% 12%, rgba(111,185,199,.08), transparent 60%),
        linear-gradient(180deg,var(--bg-top),var(--bg-bottom) 70%);
    }

    #particles-bg{position:fixed; inset:0; z-index:-3;}
    #aurora{
      position:fixed; inset:0; z-index:-2; pointer-events:none;
      background:
        radial-gradient(42% 28% at 20% 10%, rgba(111,185,199,.25), transparent 60%),
        radial-gradient(36% 24% at 85% 15%, rgba(144,180,255,.20), transparent 65%);
      filter: blur(22px);
      animation: drift 18s ease-in-out infinite alternate;
    }
    @keyframes drift{
      0%   { transform: translateY(0) translateX(0) scale(1);   opacity:.9; }
      100% { transform: translateY(18px) translateX(-14px) scale(1.02); opacity:.8; }
    }
    #vignette{
      position:fixed; inset:0; z-index:-1; pointer-events:none;
      background:
        radial-gradient(1400px 900px at 50% 10%, transparent, rgba(0,0,0,.18) 70%),
        radial-gradient(1200px 600px at 50% 120%, rgba(0,0,0,.28), transparent 70%);
    }

    .wrap{max-width:1180px; margin:0 auto; padding:26px 24px 28px;}

    .btn{
      background:rgba(255,255,255,.04); border:1px solid var(--border); color:var(--ink);
      border-radius:.8rem; padding:.55rem .9rem; font-weight:700;
      transition:background .18s ease, border-color .18s ease, transform .18s ease;
    }
    .btn:hover{background:rgba(255,255,255,.06); border-color:rgba(255,255,255,.16); transform:translateY(-1px)}
    .btn-fixed{
      position:fixed; top:14px; left:14px; z-index:9999;
      backdrop-filter: blur(6px);
      background: rgba(255,255,255,.06);
      border: 1px solid rgba(255,255,255,.14);
    }
    .btn-fixed:hover{
      background: rgba(255,255,255,.09);
      border-color: rgba(255,255,255,.22);
    }

    .title{ font-family:"Cinzel", serif; font-weight:700; letter-spacing:.5px; color:#f3f7ff; text-align:center }
    .divider{
      width:84px; height:3px; border-radius:2px; margin:.65rem auto 0;
      background:linear-gradient(90deg, rgba(217,194,143,.75), rgba(111,185,199,.65)); opacity:.75;
    }
    .muted{color:var(--muted); text-align:center}

    .bar{display:flex; flex-direction:column; gap:10px; margin-top:24px}
    @media (min-width:768px){ .bar{flex-direction:row; align-items:center} }
    .search{
      width:100%; background:rgba(255,255,255,.04); border:1px solid var(--border);
      color:var(--ink); padding:.65rem .95rem; border-radius:.8rem;
    }
    .search::placeholder{color:#8b96a3}

    #totemGrid{
      display:grid; gap:18px; margin-top:18px;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
    .card{
      background: var(--glass);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 22px 20px;
      text-align:center; backdrop-filter: blur(10px);
      transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
      box-shadow:0 14px 36px rgba(0,0,0,.30);
      position:relative; overflow:hidden; min-height:290px;
    }
    .card::after{
      content:""; position:absolute; inset:-1px; border-radius:inherit; pointer-events:none;
      background:linear-gradient(135deg, rgba(217,194,143,.16), rgba(111,185,199,.10));
      filter: blur(12px); opacity:.0; transition:opacity .25s ease;
    }
    .card:hover{ transform: translateY(-4px); border-color: rgba(255,255,255,.16); box-shadow: 0 18px 44px rgba(0,0,0,.36) }
    .card:hover::after{ opacity:.9 }

    .relic{ width:206px; height:206px; margin:0 auto; position:relative; }
    .relic::before{
      content:""; position:absolute; inset:-14px; border-radius:20px;
      background: conic-gradient(
        from 0deg,
        rgba(144,180,255,.22) 0deg,
        rgba(111,185,199,.25) 120deg,
        rgba(217,194,143,.18) 240deg,
        rgba(144,180,255,.22) 360deg
      );
      filter: blur(10px); opacity:.55; animation: spin 24s linear infinite; z-index:0;
    }
    .relic::after{
      content:""; position:absolute; inset:12px; border-radius:16px;
      background: radial-gradient(60% 60% at 50% 50%, rgba(144,180,255,.16), rgba(111,185,199,.12) 40%, transparent 72%);
      filter: blur(12px); opacity:.85; z-index:0;
    }
    .token-img{
      width:188px; height:188px; border-radius:14px; object-fit:cover;
      border:1px solid var(--border); box-shadow:0 12px 28px rgba(0,0,0,.35);
      position:relative; z-index:1; background:#0b1520; transition: transform .2s ease;
    }
    .card:hover .token-img{ transform: scale(1.03) }
    @keyframes spin{ to{ transform: rotate(360deg); } }

    .tip{
      position:absolute; bottom:110%; left:50%; transform:translateX(-50%);
      background:#0d1524; color:#dbe6f2; border:1px solid var(--border);
      padding:.34rem .55rem; border-radius:.45rem; font-size:.75rem; white-space:nowrap;
      opacity:0; pointer-events:none; transition:opacity .15s ease; box-shadow:0 10px 22px rgba(0,0,0,.32);
    }
    .card:hover .tip{ opacity:1 }

    .btn-primary{
      background: rgba(217,194,143,.10);
      border:1px solid rgba(217,194,143,.35);
      color: var(--ink);
      border-radius:.75rem;
      padding:.50rem .80rem;
      font-weight:700;
      font-size:.92rem;
      transition:background .18s ease, border-color .18s ease, transform .18s ease;
    }
    .btn-primary:hover{ background: rgba(217,194,143,.18); border-color: rgba(217,194,143,.5); transform: translateY(-1px) }

    .modal-bg{ background: rgba(7,11,18,.75); backdrop-filter: blur(8px); }
    .modal-card{ background: var(--glass); border:1px solid var(--border); border-radius:18px; }

    /* Scrollable container for similar dreams */
    #moreDreamsResult {
      max-height: 400px;
      overflow-y: auto;
      overflow-x: hidden;
      padding-right: 8px;
    }

    /* Custom scrollbar styling */
    #moreDreamsResult::-webkit-scrollbar {
      width: 8px;
    }

    #moreDreamsResult::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 10px;
    }

    #moreDreamsResult::-webkit-scrollbar-thumb {
      background: rgba(144, 180, 255, 0.4);
      border-radius: 10px;
      transition: background 0.2s ease;
    }

    #moreDreamsResult::-webkit-scrollbar-thumb:hover {
      background: rgba(144, 180, 255, 0.6);
    }

    /* For Firefox */
    #moreDreamsResult {
      scrollbar-width: thin;
      scrollbar-color: rgba(144, 180, 255, 0.4) rgba(255, 255, 255, 0.05);
    }

    #zoomedImage{
      max-width: 85vw; max-height: 80vh; width:auto; height:auto; object-fit:contain;
      border-radius:14px; border:1px solid var(--border); box-shadow:0 18px 44px rgba(0,0,0,.45);
    }

    @media (max-width: 380px){
      .relic{ width:184px; height:184px; }
      .token-img{ width:168px; height:168px; }
    }
  </style>
</head>
<body>
  <!-- Background layers -->
  <div id="particles-bg"></div>
  <div id="aurora"></div>
  <div id="vignette"></div>

  <!-- Fixed Dream World button -->
  <a href="{{ url('/imagine') }}" class="btn btn-fixed" aria-label="Back to Dream World">↩ Dream World</a>

  <div class="wrap">
    <!-- Heading -->
    <h1 class="title text-3xl md:text-4xl">Dream Totems</h1>
    <div class="divider"></div>
    <p class="muted text-sm md:text-base mt-3">
      Relics discovered in your dreams — hover to peek, open to read stories.
    </p>

    <!-- Search / Clear -->
    <div class="bar">
      <input id="totemSearch" class="search" type="text" placeholder="Search totems (e.g., mask, wings, leaf)…">
      <button id="clearSearch" class="btn">Clear</button>
    </div>

    <!-- Grid -->
    @php
      /*
        meanings[token] = [prettyMeaning, exampleSnippet, BASE_EMOTION]
        BASE_EMOTION must match what you store/query (e.g., FEAR/JOY/etc.)
      */
      $meanings = [
        'mirror' => ['Reflection & self-awareness', 'You looked into a mirror and saw your younger self.', 'CURIOSITY'],
        'wings'  => ['Freedom or ambition',         'You flew above mountains, free from all fears.',     'AMBITION'],
        'fire'   => ['Transformation or passion',   'You stood in a burning house but felt no pain.',     'TRANSFORMATION'],
        'mask'   => ['Hidden emotions or identity', 'You wore a mask in a crowded room and no one noticed.','FEAR'],
        'cloud'  => ['Calm & stillness',            'You watched slow-moving clouds in complete silence.','CALM'],
        'tear'   => ['Deep sadness or grief',       'You shed a tear that echoed across a silent field.','SADNESS'],
        'moon'   => ['Nostalgia & memories',        'You walked under a full moon and remembered a past love.','NOSTALGIA'],
        'bolt'   => ['Sudden change or surprise',   'You were struck by lightning but felt alive.','SURPRISE'],
        'anchor' => ['Trust & grounding',           'You dropped anchor in stormy seas and found peace.','TRUST'],
        'quill'  => ['Gratitude & expression',      'You wrote a heartfelt letter under candlelight.','GRATITUDE'],
        'compass'=> ['Curiosity & direction',       'You held a spinning compass pointing to the unknown.','CURIOSITY'],
        'star'   => ['Awe & wonder',                'You stood beneath a sky full of falling stars.','AWE'],
        'shield' => ['Courage & protection',        'You held a glowing shield against a dark force.','PROTECTION'],
        'leaf'   => ['Hope & renewal',              'A single green leaf grew in a barren land.','HOPE'],
        'crest'  => ['Pride & legacy',              'You found your family crest on an ancient tapestry.','PRIDE'],
        'heart'  => ['Love & connection',           'You held a glowing heart that warmed the coldest night.','LOVE'],
      ];
    @endphp

    <div id="totemGrid">
      @if(isset($tokens) && count($tokens))
        @foreach($tokens as $token)
          @php
            $key = strtolower($token);
            $pretty = $meanings[$key][0] ?? 'Symbolic dream totem';
            $example = $dreamSnippets[$key] ?? ($meanings[$key][1] ?? 'No dream found.');
            $baseEmotion = $meanings[$key][2] ?? strtoupper($key); // fallback
          @endphp
          <article class="card"
                   data-token="{{ $key }}"
                   data-emotion="{{ $baseEmotion }}"
                   data-aos="fade-up" data-aos-delay="60">
            <div class="tip">{{ $pretty }}</div>

            <div class="relic">
              <img src="/images/totems/{{ $key }}.png"
                   alt="{{ ucfirst($token) }}"
                   class="token-img"
                   onclick="event.stopPropagation(); showImageModal(this.src)">
            </div>

            <h3 class="mt-3 text-lg font-extrabold">{{ ucfirst($token) }}</h3>
            <p class="mt-1 text-sm muted">{{ $pretty }}</p>

            <button
              class="btn-primary mt-4"
              onclick="openModal('{{ ucfirst($token) }}','{{ $pretty }}', `{{ $example }}`, '{{ $baseEmotion }}')">
              View Meaning & Stories
            </button>
          </article>
        @endforeach
      @else
        <p class="muted text-center mt-10">You haven’t collected any tokens yet. Submit a dream to begin.</p>
      @endif
    </div>
  </div>

  <!-- Text Modal -->
  <div id="totemModal" class="modal-bg hidden fixed inset-0 z-50 flex items-center justify-center px-4"
       role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-card w-full max-w-xl p-6 relative">
      <button onclick="closeModal()" class="absolute top-3 right-4 text-xl muted hover:text-red-300" aria-label="Close">&times;</button>
      <h2 id="modalTitle" class="text-2xl font-extrabold" style="font-family:'Cinzel',serif">Totem</h2>
      <p id="modalMeaning" class="muted mt-1">Meaning</p>
      <p id="modalDream" class="mt-3 text-blue-50 italic"></p>
      <div class="mt-5 text-center">
        <button onclick="loadMatchingDreams()" class="btn">View more dreams like this</button>
        <div id="moreDreamsResult" class="mt-4 text-left"></div>
      </div>
    </div>
  </div>

  <!-- Image Zoom Modal (constrained) -->
  <div id="imageZoomModal" class="hidden fixed inset-0 z-50 modal-bg flex items-center justify-center"
       role="dialog" aria-modal="true" aria-label="Zoomed image" onclick="handleImageBackdrop(event)">
    <img id="zoomedImage" src="" alt="">
    <button onclick="closeImageModal()" class="absolute top-4 right-6 text-white text-3xl hover:text-red-300" aria-label="Close image">&times;</button>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.11.0/tsparticles.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({ once:true });

    tsParticles.load("particles-bg", {
      fpsLimit: 60,
      background: { color: "transparent" },
      particles: {
        number: { value: 28, density: { enable:true, area: 1100 } },
        color: { value: ["#6b7280","#94a3b8"] },
        links: { enable:true, distance: 125, color:"#475569", opacity:.22, width:1 },
        move: { enable:true, speed: .7, outModes:{ default:"out" } },
        opacity: { value:.5 }, size: { value: { min:1, max:2 } }
      },
      interactivity: { events: { onHover:{ enable:true, mode:"grab" }, resize:true },
        modes: { grab: { distance: 118, links: { opacity:.35 } } } },
      detectRetina: true
    });

    // ---- Modal logic ----
    let currentEmotion = ""; // canonical label e.g. "FEAR"
 
    function openModal(title, meaning, dream, baseEmotion){
      document.getElementById('modalTitle').innerText = title;
      document.getElementById('modalMeaning').innerText = meaning;
      document.getElementById('modalDream').innerText = dream;
      document.getElementById('moreDreamsResult').innerHTML = "";
      currentEmotion = String(baseEmotion || title).trim().toUpperCase(); // normalize
      document.getElementById('totemModal').classList.remove('hidden');
    }
    function closeModal(){ document.getElementById('totemModal').classList.add('hidden'); }
    document.getElementById('totemModal').addEventListener('click', (e)=>{
      const card=document.querySelector('#totemModal .modal-card'); if(!card.contains(e.target)) closeModal();
    });

    // Fetch fresh matches on demand (server should return HTML partial)
    function loadMatchingDreams(){
      const url = `/dreams/similar?emotion=${encodeURIComponent(currentEmotion)}`;
      document.getElementById('moreDreamsResult').innerHTML = '<p class="opacity-70 text-sm">Loading…</p>';
      fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.text())
        .then(html => { document.getElementById('moreDreamsResult').innerHTML = html; })
        .catch(() => { document.getElementById('moreDreamsResult').innerHTML = '<p class="text-red-300">Error loading dreams.</p>'; });
    }

    // Image zoom
    function showImageModal(src){
      const img = document.getElementById('zoomedImage');
      img.src = src;
      document.getElementById('imageZoomModal').classList.remove('hidden');
    }
    function closeImageModal(){
      document.getElementById('imageZoomModal').classList.add('hidden');
    }
    function handleImageBackdrop(e){
      const img = document.getElementById('zoomedImage');
      if(!img.contains(e.target)) closeImageModal();
    }

    // Search
    const search = document.getElementById('totemSearch');
    const clearBtn = document.getElementById('clearSearch');
    const grid = document.getElementById('totemGrid');
    function applyFilter(){
      const q = (search.value||"").trim().toLowerCase();
      grid?.querySelectorAll('[data-token]').forEach(card=>{
        const t = card.getAttribute('data-token');
        card.style.display = (!q || t.includes(q)) ? '' : 'none';
      });
    }
    search.addEventListener('input', applyFilter);
    clearBtn.addEventListener('click', ()=>{ search.value=''; applyFilter(); });
  </script>
</body>
</html>

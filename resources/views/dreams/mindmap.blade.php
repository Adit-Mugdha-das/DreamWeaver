<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dream Mind Map</title>
  @vite('resources/css/app.css')

  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    body { background:#0f172a; color:#e2e8f0; min-height:100vh; }
    #vanta-bg { position:fixed; inset:0; z-index:-1; }
    .shell { max-width:1100px; margin:0 auto; padding:2rem 1rem 4rem; }
    .card { background:rgba(2,6,23,.6); border:1px solid rgba(236,72,153,.2);
            border-radius:1rem; padding:1rem; }
    textarea { background:#0b1220; border:1px solid rgba(236,72,153,.2); color:#e2e8f0; }
    .btn { background:#ec4899; color:white; border-radius:.75rem; padding:.6rem 1rem; }
    .btn:hover { background:#f472b6; }
  </style>
</head>
<body>
  <div id="vanta-bg"></div>

  <div class="shell">
    <div class="flex items-center justify-between mb-6">
      <a href="{{ route('imagine.portal') }}" class="px-3 py-2 rounded-lg bg-black/50 border border-fuchsia-400/20">
        ← Back to Dream World
      </a>
      <h1 class="text-2xl font-bold">Dream Mind Map</h1>
    </div>

    <div class="grid md:grid-cols-2 gap-6" data-aos="fade-up">
      {{-- Left: Editor --}}
      <div class="card">
        <form method="POST" action="{{ route('mindmap.save', $dream) }}" class="space-y-3">
          @csrf
          <label class="text-sm text-slate-300">Mind Map (Markdown bullets)</label>

          @php
            $defaultMd = "- Dream\n  - People\n  - Places\n  - Symbols\n  - Emotions\n";
            // If DB empty: show a nice starter using REAL newlines (not '\n' text)
            $md = old('mindmap_md', $dream->mindmap_md ?: $defaultMd);
          @endphp

          <textarea name="mindmap_md" rows="18" class="w-full rounded-xl p-3" id="mm-src">{{ $md }}</textarea>

          <div class="flex items-center gap-3">
            <button class="btn" type="submit">Save Mind Map</button>
            @if(session('success'))
              <span class="text-emerald-400 text-sm">{{ session('success') }}</span>
            @endif
          </div>
        </form>

        <p class="text-xs text-slate-400 mt-3">
          Tip: Use bullets and indentation, e.g.<br>
          - Dream<br>
          &nbsp;&nbsp;- People<br>
          &nbsp;&nbsp;- Places<br>
          &nbsp;&nbsp;- Symbols<br>
          &nbsp;&nbsp;- Emotions
        </p>
      </div>

      {{-- Right: Live Preview --}}
      <div class="card">
        <div class="text-sm text-slate-400 mb-2">Live preview</div>
        <div id="mm-view" class="w-full min-h-[520px] rounded-xl border border-fuchsia-400/20 p-2 bg-slate-950"></div>
      </div>
    </div>
  </div>

  {{-- Scripts --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.cells.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/markmap-autoloader"></script>
  <script>
    // Background and AOS
    VANTA.CELLS({
      el: "#vanta-bg", mouseControls:true, touchControls:true, gyroControls:false,
      minHeight: 200.00, minWidth: 200.00, scale: 1.0, scaleMobile: 1.0,
      color1: 0x14b8a6, color2: 0xec4899, size: 2.0, backgroundColor: 0x0f172a
    });
    AOS.init({ once:true, duration:800, easing:'ease-out-cubic' });

    // Markmap render
    const src = document.getElementById('mm-src');
    const mount = document.getElementById('mm-view');

    function escapeHTML(s){ return s.replace(/&/g,'&amp;').replace(/</g,'&lt;'); }

    function render() {
      const content = (src.value || "").trim() || `- Dream
  - People
  - Places
  - Symbols
  - Emotions`;
      mount.innerHTML = `<pre class="markmap">${escapeHTML(content)}</pre>`;
      window.markmap?.autoLoader?.renderAll();
    }

    src.addEventListener('input', render);
    document.addEventListener('DOMContentLoaded', render);
  </script>
</body>
</html>

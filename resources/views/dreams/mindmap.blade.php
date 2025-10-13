<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dream Mind Map</title>
  @vite('resources/css/app.css')
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    body { background:#0f172a; color:#e2e8f0; min-height:100vh; overflow-x:hidden; }
    #vanta-bg { position:fixed; inset:0; z-index:-1; }
    .shell { max-width:1600px; margin:0 auto; padding:2rem 1rem 4rem; }

    .card {
      background:rgba(2,6,23,.6);
      border:1px solid rgba(236,72,153,.2);
      border-radius:1rem;
      padding:1.25rem;
      width:100%;
      box-shadow:0 0 20px rgba(236,72,153,.08);
    }

    .grid { display:flex; flex-wrap:wrap; gap:2rem; justify-content:space-between; }
    .grid > .card { flex:1 1 48%; min-width:640px; }

    textarea{
      background:#0b1220;border:1px solid rgba(236,72,153,.2);color:#e2e8f0;
      width:100%;font-size:1rem;line-height:1.5;border-radius:.75rem;resize:none;padding:.75rem;
    }
    .btn{background:#ec4899;color:#fff;border-radius:.75rem;padding:.6rem 1rem;transition:.3s}
    .btn:hover{background:#f472b6;transform:scale(1.04)}

    /* ==== PREVIEW CARD: header + full-bleed host =================== */
    .card-tight { padding:0; overflow:visible; }
    .card-head  { padding:1rem 1.25rem; }
    .mm-host{
      width:100%;
      height:820px;
      background:#020617;
      border-top:1px solid rgba(236,72,153,.2);
      border-radius:0 0 1rem 1rem;
      overflow:visible !important; /* allow mindmap to be visible when panned outside container */
      position:relative;
    }

    /* Make the SVG fill the host but allow overflow */
    .mm-host svg{ 
      width:100% !important; 
      height:100% !important; 
      display:block; 
      overflow:visible !important;
    }
    
    /* Remove clipping from all SVG groups */
    .mm-host svg g{
      overflow:visible !important;
    }

    /* Readability outline for labels */
    svg.markmap text{
      paint-order:stroke fill;
      stroke-width:.8px;
      transition:fill .25s,filter .25s,stroke .25s;
    }
  </style>
</head>
<body>
  <div id="vanta-bg"></div>

  <div class="shell">
    <div class="flex items-center justify-between mb-8">
      <a href="{{ route('imagine.portal') }}" class="px-3 py-2 rounded-lg bg-black/50 border border-fuchsia-400/20 hover:scale-105 transition">
        ← Back to Dream World
      </a>
      <h1 class="text-3xl font-bold text-center w-full">Dream Mind Map</h1>
    </div>

    <div class="grid" data-aos="fade-up">
      <!-- Left: Editor -->
      <div class="card">
        <form method="POST" action="{{ route('mindmap.save', $dream) }}" class="space-y-3">
          @csrf
          <label class="text-sm text-slate-300">Mind Map (Markdown bullets)</label>
          @php
            $defaultMd = "- Dream\n  - People\n  - Places\n  - Symbols\n  - Emotions\n";
            $md = old('mindmap_md', $dream->mindmap_md ?: $defaultMd);
          @endphp
          <textarea name="mindmap_md" rows="22" id="mm-src">{{ $md }}</textarea>

          <div class="flex items-center gap-3 mt-3">
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

      <!-- Right: Live Preview -->
      <div class="card card-tight">
        <div class="card-head flex items-center justify-between">
          <div class="text-sm text-slate-400">Live preview</div>
          <button id="exportBtn" class="btn">Export as PNG</button>
        </div>
        <div id="mm-view" class="mm-host"></div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.cells.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/markmap-autoloader"></script>

  <script>
    // Background + AOS
    VANTA.CELLS({
      el:"#vanta-bg", mouseControls:true, touchControls:true, gyroControls:false,
      minHeight:200, minWidth:200, scale:1.0, scaleMobile:1.0,
      color1:0x14b8a6, color2:0xec4899, size:2.0, backgroundColor:0x0f172a
    });
    AOS.init({ once:true, duration:800, easing:'ease-out-cubic' });

    const src   = document.getElementById('mm-src');
    const host  = document.getElementById('mm-view');

    const THEME_COLORS = { people:"#60a5fa", places:"#f59e0b", symbols:"#a855f7", emotions:"#f43f5e" };
    const ALIGN_LEFT = true; // set false to center the map horizontally

    const escapeHTML = s => s.replace(/&/g,'&amp;').replace(/</g,'&lt;');

    function applyGlow(t,color){
      t.setAttribute("fill",color);
      t.setAttribute("stroke",color);
      t.style.filter=`drop-shadow(0 0 6px ${color}66) drop-shadow(0 0 12px ${color}44)`;
    }

    function recolorMindmap(){
      document.querySelectorAll("#mm-view svg text").forEach(t=>{
        const label=(t.textContent||"").toLowerCase();
        t.removeAttribute("fill"); t.removeAttribute("stroke"); t.style.filter="";
        if(label.includes("people"))   return applyGlow(t,THEME_COLORS.people);
        if(label.includes("places"))   return applyGlow(t,THEME_COLORS.places);
        if(label.includes("symbols"))  return applyGlow(t,THEME_COLORS.symbols);
        if(label.includes("emotions")) return applyGlow(t,THEME_COLORS.emotions);
      });
    }

    // Go to deepest content group
    function getContentGroup(svg){
      if(!svg) return null;
      let g=svg.querySelector("g");
      while(g && g.querySelector("g")) g=g.querySelector("g");
      return g;
    }

    // Fill host and align left-middle
    function fitMindmap(){
      const svg = document.querySelector("#mm-view svg");
      if(!svg) return;
      const g = getContentGroup(svg);
      if(!g) return;

      const padX=40, padY=40;
      const cw = host.clientWidth  - padX*2;
      const ch = host.clientHeight - padY*2;

      const bbox = g.getBBox();
      if(bbox.width===0 || bbox.height===0) return;

      const scale = Math.min(cw / bbox.width, ch / bbox.height) * 1.2;
      const tx = ALIGN_LEFT
        ? (padX - bbox.x * scale)
        : (padX + (cw - bbox.width * scale)/2 - bbox.x * scale);
      const ty = padY + (ch - bbox.height * scale)/2 - bbox.y * scale;

      g.setAttribute("transform", `translate(${tx},${ty}) scale(${scale})`);

      // Set viewBox to be 3x the container size to allow panning beyond edges
      const vbWidth = host.clientWidth * 3;
      const vbHeight = host.clientHeight * 3;
      const vbX = -host.clientWidth;
      const vbY = -host.clientHeight;
      
      svg.setAttribute("viewBox", `${vbX} ${vbY} ${vbWidth} ${vbHeight}`);
      svg.setAttribute("preserveAspectRatio", "xMidYMid meet");
      svg.style.overflow = "visible";
    }

    // === IMPORTANT: remove Markmap's clipping so nothing gets cut ===
    function relaxClip() {
      const svg = document.querySelector('#mm-view svg');
      if (!svg) return;

      // Force overflow visible on SVG and all children
      svg.style.overflow = "visible";
      svg.querySelectorAll('*').forEach(el => {
        if(el.style) el.style.overflow = "visible";
      });

      // 1) Remove clip-path attributes on ALL elements
      svg.querySelectorAll('[clip-path]').forEach(el => {
        el.removeAttribute('clip-path');
        el.style.clipPath = "none";
      });

      // 2) Remove any <clipPath> defs entirely (so nothing can re-clip)
      svg.querySelectorAll('clipPath').forEach(cp => cp.remove());

      // 3) Remove masks
      svg.querySelectorAll('[mask]').forEach(el => el.removeAttribute('mask'));
      svg.querySelectorAll('mask').forEach(m => m.remove());

      // 4) Some builds add a mask-like rect — blow it up just in case
      svg.querySelectorAll('defs rect').forEach(r => {
        r.setAttribute('x', '-100000');
        r.setAttribute('y', '-100000');
        r.setAttribute('width',  '200000');
        r.setAttribute('height', '200000');
      });
    }

    function afterRenderAdjustments() {
      recolorMindmap();
      fitMindmap();
      relaxClip();  // <-- kills the invisible clipping line
    }

    function render(){
      const content=(src.value||"").trim() || `- Dream
  - People
  - Places
  - Symbols
  - Emotions`;
      host.innerHTML=`<pre class="markmap">${escapeHTML(content)}</pre>`;
      window.markmap?.autoLoader?.renderAll();
      setTimeout(afterRenderAdjustments, 350);
    }

    // events
    src.addEventListener('input', render);
    window.addEventListener('resize', () => setTimeout(afterRenderAdjustments, 100));
    document.addEventListener('DOMContentLoaded', render);

    // keep colors/clip fresh if nodes open/close
    setInterval(afterRenderAdjustments, 1200);

    // (Optional) catch internal re-renders injected by markmap
    new MutationObserver(() => { relaxClip(); })
      .observe(document.getElementById('mm-view'), { childList: true, subtree: true });

    // -------- PNG EXPORT (no SVG file) --------
    document.getElementById("exportBtn").onclick = () => {
      const svg = document.querySelector("#mm-view svg");
      if(!svg){ alert("Please render the mind map first."); return; }

      // Serialize SVG
      const xml = new XMLSerializer().serializeToString(svg);

      // Make an image from the SVG XML
      const img = new Image();
      const vb  = svg.viewBox && svg.viewBox.baseVal ? svg.viewBox.baseVal : null;
      const w   = vb ? vb.width  : host.clientWidth;
      const h   = vb ? vb.height : host.clientHeight;

      // Higher-res export (2x)
      const scale = 2;
      const canvas = document.createElement('canvas');
      canvas.width  = Math.max(1, Math.floor(w * scale));
      canvas.height = Math.max(1, Math.floor(h * scale));
      const ctx = canvas.getContext('2d');

      // Background color (match live area)
      ctx.fillStyle = '#020617';
      ctx.fillRect(0,0,canvas.width,canvas.height);

      img.onload = () => {
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        const url = canvas.toDataURL('image/png');
        const a = document.createElement('a');
        a.href = url;
        a.download = 'dream_mindmap.png';
        a.click();
      };

      // Important: proper data URL encoding
      const svgURL = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(xml);
      img.src = svgURL;
    };
  </script>
</body>
</html>

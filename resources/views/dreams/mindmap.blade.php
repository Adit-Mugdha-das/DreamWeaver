<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dream Mind Map</title>
  @vite('resources/css/app.css')
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
    :root{
      /* Harmonized palette (teal ➜ cyan ➜ violet) */
      --bg-deep: #0b1220;
      --bg-card: rgba(9,13,23,.56);
      --stroke:  rgba(139,92,246,.25);     /* violet */
      --teal:    #14b8a6;
      --cyan:    #06b6d4;
      --violet:  #8b5cf6;

      --btn-grad: linear-gradient(135deg, var(--teal) 0%, var(--violet) 100%);
      --btn-grad-soft: linear-gradient(135deg, #10b98133, #8b5cf633);
      --text: #e2e8f0;
      --muted: #94a3b8;
    }

    * { box-sizing: border-box; }
    body{
      background: var(--bg-deep);
      color: var(--text);
      min-height: 100vh;
      overflow-x: hidden;
      font-smooth: always;
      -webkit-font-smoothing: antialiased;
    }

    /* 3D background host */
    #vanta-bg{
      position: fixed; inset: 0; z-index: -2;
    }
    /* Soft animated radial glow overlay (adds depth) */
    .bg-glow::before{
      content:""; position: fixed; inset: -10%;
      background:
        radial-gradient(60vw 60vw at 10% 20%, rgba(20,184,166,.14), transparent 55%),
        radial-gradient(70vw 70vw at 90% 10%, rgba(139,92,246,.12), transparent 60%),
        radial-gradient(80vw 80vw at 50% 90%, rgba(6,182,212,.10), transparent 65%);
      pointer-events:none; z-index:-1; animation: float 16s ease-in-out infinite alternate;
    }
    @keyframes float{
      from{ transform: translateY(-1.5rem) }
      to  { transform: translateY(1.5rem)  }
    }

    .shell{ max-width: 1600px; margin: 0 auto; padding: 2rem 1rem 4rem; }

    /* Glass cards */
    .card{
      background: var(--bg-card);
      border: 1px solid var(--stroke);
      border-radius: 1rem;
      padding: 1.25rem;
      width: 100%;
      box-shadow:
        0 10px 30px rgba(0,0,0,.35),
        0 0 0 1px rgba(255,255,255,.02) inset;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }

    .grid{ display:flex; flex-wrap:wrap; gap:2rem; justify-content:space-between; }
    .grid > .card{ flex:1 1 48%; min-width: 640px; }

    /* Inputs */
    textarea{
      width: 100%; resize: none; padding: .9rem 1rem;
      background: rgba(2,6,23,.75);
      border: 1px solid var(--stroke);
      color: var(--text);
      border-radius: .8rem; line-height: 1.55; font-size: .98rem;
      outline: none;
      transition: box-shadow .25s, border-color .25s;
    }
    textarea:focus{
      border-color: var(--cyan);
      box-shadow: 0 0 0 3px rgba(6,182,212,.18), 0 8px 22px rgba(6,182,212,.12);
    }

    /* Buttons – gradient + glow, matching background */
    .btn{
      position: relative; display: inline-flex; align-items: center; justify-content: center;
      gap:.5rem; padding:.7rem 1.05rem; border-radius:.9rem;
      color: white; font-weight: 600; letter-spacing:.2px;
      background: var(--btn-grad); border: 1px solid rgba(255,255,255,.08);
      box-shadow: 0 10px 25px rgba(139,92,246,.22), 0 2px 0 rgba(255,255,255,.06) inset;
      transition: transform .18s ease, filter .18s ease, box-shadow .18s ease;
    }
    .btn:hover{ transform: translateY(-1px); filter: brightness(1.04);
      box-shadow: 0 16px 32px rgba(139,92,246,.28), 0 0 0 3px rgba(20,184,166,.2);
    }
    .btn-ghost{
      background: var(--btn-grad-soft);
      color: var(--text);
      border: 1px solid var(--stroke);
      box-shadow: 0 8px 20px rgba(0,0,0,.25);
    }
    .btn-ghost:hover{ box-shadow: 0 12px 26px rgba(0,0,0,.35), 0 0 0 3px rgba(139,92,246,.18); }

    /* Preview */
    .card-tight{ padding: 0; overflow: visible; }
    .card-head{ padding: 1rem 1.25rem; border-bottom: 1px solid var(--stroke); }
    .mm-host{
      width:100%; height:820px; position:relative;
      background:#020617;
      border-radius: 0 0 1rem 1rem;
      overflow: visible !important;
    }
    .mm-host svg{ width:100% !important; height:100% !important; display:block; overflow:visible !important; }
    .mm-host svg g{ overflow:visible !important; }

    small.muted{ color: var(--muted); }
    h1,h2{ text-shadow: 0 6px 24px rgba(0,0,0,.4); }
  </style>
</head>
<body class="bg-glow">
  <div id="vanta-bg"></div>

  <div class="shell">
    <div class="flex items-center justify-between mb-8">
      <a href="{{ route('imagine.portal') }}" class="btn-ghost px-3 py-2">
        ←Back
      </a>
      <h1 class="text-3xl font-bold text-center w-full">Dream Mind Map</h1>
      <span class="hidden md:block" aria-hidden="true"></span>
    </div>

    <div class="grid" data-aos="fade-up">
      {{-- Left: Editor --}}
      <div class="card">
        <form id="mindmapForm" method="POST" action="{{ route('mindmap.save', $dream) }}" class="space-y-3">
          @csrf
          <label class="text-sm"><small class="muted">Mind Map (Markdown bullets)</small></label>
          @php
            $defaultMd = "- Dream\n  - People\n  - Places\n  - Symbols\n  - Emotions\n";
            $md = old('mindmap_md', $dream->mindmap_md ?: $defaultMd);
          @endphp
          <textarea name="mindmap_md" rows="22" id="mm-src">{{ $md }}</textarea>

          <div class="flex items-center gap-3 mt-3">
            <button class="btn" type="submit" id="saveBtn">Save Mind Map</button>
            <button class="btn btn-ghost" type="button" id="autoGenBtn">Auto-Generate from Dream</button>
            <span id="saveStatus" class="text-emerald-400 text-sm" style="display:none;"></span>
          </div>
        </form>

        @if(isset($savedMindMaps) && count($savedMindMaps) > 0)
          <button class="btn btn-ghost" onclick="toggleSavedMindMaps()" style="margin-top:1rem; width:100%">
            View Saved Mind Maps ({{ count($savedMindMaps) }})
          </button>
        @endif

        <p class="text-xs mt-3"><small class="muted">
          Tip: Use bullets and indentation, e.g.<br>
          - Dream<br>
          &nbsp;&nbsp;- People<br>
          &nbsp;&nbsp;- Places<br>
          &nbsp;&nbsp;- Symbols<br>
          &nbsp;&nbsp;- Emotions
        </small></p>
      </div>

      {{-- Right: Live Preview --}}
      <div class="card card-tight">
        <div class="card-head flex items-center justify-between">
          <div class="text-sm"><small class="muted">Live preview</small></div>
        </div>
        <div id="mm-view" class="mm-host"></div>
      </div>
    </div>

    {{-- Saved list (unchanged) --}}
    @if(isset($savedMindMaps) && count($savedMindMaps) > 0)
      <div id="savedMindMapsSection" style="display:none; margin-top:2rem;" data-aos="fade-up">
        <h2 class="text-2xl font-bold mb-4 text-center" style="color:var(--teal)"> Your Saved Mind Maps</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          @foreach($savedMindMaps as $saved)
            <div class="card" style="padding:1rem; transition:transform .3s;"
                 onmouseover="this.style.transform='translateY(-2px) scale(1.02)'"
                 onmouseout="this.style.transform='none'">
              <h3 class="text-lg font-bold mb-2" style="color:var(--violet)">{{ $saved->title }}</h3>
              <div class="text-xs" style="color:var(--muted);">
                Updated: {{ $saved->updated_at->diffForHumans() }}
              </div>
              <div id="mini-preview-{{ $saved->id }}" class="mini-mindmap-preview"
                   style="height: 300px; background:#020617; border-radius:.6rem; overflow:visible; margin: .8rem 0 1rem; position:relative;">
                <div style="position:absolute; inset:0; display:grid; place-items:center; color:#64748b; font-size:.875rem;">
                  Click Load to view
                </div>
              </div>
              <button onclick="loadMindMapPreview({{ $saved->id }}, {{ json_encode($saved->mindmap_md) }})"
                      class="btn" style="width:100%; font-size:.9rem; padding:.55rem;">
                 Load Mind Map
              </button>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </div>

  {{-- Scripts --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <!-- Swap to a 3D-ish network effect -->
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/markmap-autoloader"></script>

  <script>
    // 3D-ish background (Vanta NET), tuned to our palette
    VANTA.NET({
      el: "#vanta-bg",
      mouseControls: true, touchControls: true, gyroControls: false,
      minHeight: 200.00, minWidth: 200.00, scale: 1.0, scaleMobile: 1.0,
      color: 0x8b5cf6,              // violet lines
      backgroundColor: 0x0b1220,    // deep base
      points: 9.0,                   // density
      maxDistance: 22.0,
      spacing: 18.0
    });

    AOS.init({ once:true, duration:800, easing:'ease-out-cubic' });

    const src  = document.getElementById('mm-src');
    const host = document.getElementById('mm-view');

    const THEME_COLORS = {
      people:"#60a5fa", places:"#f59e0b", symbols:"#a855f7", emotions:"#f43f5e",
      joy:"#fbbf24", happy:"#fbbf24", excited:"#fbbf24",
      fear:"#8b5cf6", scared:"#8b5cf6", anxious:"#8b5cf6", worried:"#8b5cf6",
      sad:"#3b82f6", sadness:"#3b82f6", melancholy:"#3b82f6", depressed:"#3b82f6",
      anger:"#ef4444", angry:"#ef4444", frustrated:"#ef4444", rage:"#ef4444",
      love:"#ec4899", loving:"#ec4899", affection:"#ec4899", romantic:"#ec4899",
      surprise:"#06b6d4", surprised:"#06b6d4", shocked:"#06b6d4", amazed:"#06b6d4",
      calm:"#10b981", peaceful:"#10b981", serene:"#10b981", relaxed:"#10b981",
      confusion:"#a855f7", confused:"#a855f7", uncertain:"#a855f7", lost:"#a855f7"
    };
    const ALIGN_LEFT = true;
    const escapeHTML = s => s.replace(/&/g,'&amp;').replace(/</g,'&lt;');

    const dreamData = {
      title: @json($dream->title ?? ''),
      content: @json($dream->content ?? ''),
      emotion: @json($dream->emotion_summary ?? ''),
      interpretation: @json($dream->short_interpretation ?? ''),
      story: @json($dream->story_generation ?? ''),
      narrative: @json($dream->long_narrative ?? '')
    };

    function applyGlow(t,color){
      t.setAttribute("fill",color); t.setAttribute("stroke",color);
      t.style.filter = `drop-shadow(0 0 6px ${color}66) drop-shadow(0 0 12px ${color}44)`;
    }
    function recolorMindmap(){
      document.querySelectorAll("#mm-view svg text").forEach(t=>{
        const label=(t.textContent||"").toLowerCase();
        t.removeAttribute("fill"); t.removeAttribute("stroke"); t.style.filter="";
        for(const [k,c] of Object.entries(THEME_COLORS)){ if(label.includes(k)){ applyGlow(t,c); return; } }
      });
    }

    function autoGenerateMindMap(){
      if(!dreamData.content && !dreamData.emotion) return null;
      let md = `- ${dreamData.title || 'My Dream'}\n`;
      if(dreamData.emotion){
        const emos = extractEmotions(dreamData.emotion);
        if(emos.length){ md += `  - Emotions\n`; emos.forEach(e => md += `    - ${e}\n`); }
      }
      if(dreamData.interpretation){
        const syms = extractSymbols(dreamData.interpretation);
        if(syms.length){ md += `  - Symbols & Meanings\n`; syms.forEach(s => md += `    - ${s}\n`); }
      }
      if(dreamData.story){
        const ths = extractThemes(dreamData.story);
        if(ths.length){ md += `  - Themes\n`; ths.forEach(t => md += `    - ${t}\n`); }
      }
      if(dreamData.content){
        const lines = dreamData.content.split(/[.!?]/).filter(l=>l.trim().length>10).slice(0,3);
        if(lines.length){ md += `  - Dream Content\n`; lines.forEach(l=>{ const t=l.trim().slice(0,60); md+=`    - ${t}${t.length===60?'...':''}\n`; }); }
      }
      return md;
    }
    function extractEmotions(s){const k=['joy','happy','fear','scared','sad','anger','angry','love','surprise','calm','anxious','excited','peaceful','confused','worried'];const L=s.toLowerCase();const o=[];k.forEach(x=>{if(L.includes(x)&&!o.includes(x))o.push(x[0].toUpperCase()+x.slice(1))});return o.slice(0,5)}
    function extractSymbols(t){const p=[/([A-Z][a-z]+(?:\s+[a-z]+)?)\s+(?:represents|symbolizes|means|suggests|indicates)\s+([^,.;]+)/gi,/(?:represents|symbolizes|means)\s+([^,.;]+)/gi];const o=[];p.forEach(r=>{let m;while((m=r.exec(t))&&o.length<5){const s=m[1]?`${m[1]}: ${m[2]}`:m[1]||m[0];if(s.length<60)o.push(s)}});return o}
    function extractThemes(t){return t.split(/[.!?]/).filter(s=>s.trim().length>20&&s.trim().length<100).slice(0,4)}

    function getContentGroup(svg){ if(!svg) return null; let g=svg.querySelector("g"); while(g && g.querySelector("g")) g=g.querySelector("g"); return g; }

    function fitMindmap(){
      const svg=document.querySelector("#mm-view svg"); if(!svg) return;
      const g=getContentGroup(svg); if(!g) return;
      const padX=40, padY=40, cw=host.clientWidth-padX*2, ch=host.clientHeight-padY*2;
      const bb=g.getBBox(); if(!bb.width||!bb.height) return;
      const scale=Math.min(cw/bb.width, ch/bb.height)*1.2;
      const tx=(ALIGN_LEFT? (padX - bb.x*scale) : (padX + (cw - bb.width*scale)/2 - bb.x*scale));
      const ty= padY + (ch - bb.height*scale)/2 - bb.y*scale;
      g.setAttribute("transform",`translate(${tx},${ty}) scale(${scale})`);
      const vbW=host.clientWidth*3, vbH=host.clientHeight*3, vbX=-host.clientWidth, vbY=-host.clientHeight;
      svg.setAttribute("viewBox",`${vbX} ${vbY} ${vbW} ${vbH}`); svg.setAttribute("preserveAspectRatio","xMidYMid meet"); svg.style.overflow="visible";
    }
    function relaxClip(){
      const svg=document.querySelector('#mm-view svg'); if(!svg) return;
      svg.style.overflow="visible"; svg.querySelectorAll('*').forEach(el=>{if(el.style) el.style.overflow="visible";});
      svg.querySelectorAll('[clip-path]').forEach(el=>{el.removeAttribute('clip-path'); el.style.clipPath="none";});
      svg.querySelectorAll('clipPath,mask').forEach(n=>n.remove());
    }
    function afterRenderAdjustments(){ recolorMindmap(); fitMindmap(); relaxClip(); }

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

    src.addEventListener('input', render);
    window.addEventListener('resize', () => setTimeout(afterRenderAdjustments, 100));
    document.addEventListener('DOMContentLoaded', render);

    document.getElementById('autoGenBtn').addEventListener('click', () => {
      const generated = autoGenerateMindMap();
      if(generated){ src.value = generated; render(); }
      else{ alert('No dream data to generate from.'); }
    });

    // saved section toggler
    window.toggleSavedMindMaps = () => {
      const s = document.getElementById('savedMindMapsSection');
      s.style.display = s.style.display === 'none' ? 'block' : 'none';
    };

    window.loadMindMapPreview = (id, markdown) => {
      const c = document.getElementById(`mini-preview-${id}`); if(!c) return;
      c.innerHTML = `<pre class="markmap">${escapeHTML(markdown)}</pre>`;
      window.markmap?.autoLoader?.renderAll();
      setTimeout(()=>{ const svg=c.querySelector('svg'); if(!svg) return; svg.style.width='100%'; svg.style.height='100%'; recolorMindmap(); }, 400);
    };

    // Optional: AJAX save (unchanged from your build)
    document.getElementById('mindmapForm').addEventListener('submit', async (e)=>{
      e.preventDefault();
      const saveBtn=document.getElementById('saveBtn'); const saveStatus=document.getElementById('saveStatus');
      const formData=new FormData(e.target); saveBtn.disabled=true; saveBtn.textContent='Saving...';
      try{
        const res=await fetch(e.target.action,{method:'POST',body:formData,headers:{'X-Requested-With':'XMLHttpRequest'}});
        const data=await res.json();
        if(res.ok){ saveStatus.textContent='✓ Mind map saved!'; saveStatus.style.display='inline'; saveStatus.style.color='#10b981';
          setTimeout(()=>saveStatus.style.display='none',3000); setTimeout(()=>window.location.reload(), 800);
        }else{ throw new Error(data.message||'Save failed'); }
      }catch(err){ saveStatus.textContent='✗ '+err.message; saveStatus.style.display='inline'; saveStatus.style.color='#ef4444'; }
      finally{ saveBtn.disabled=false; saveBtn.textContent='Save Mind Map'; }
    });

    // keep colors/clip fresh on internal rerenders
    setInterval(afterRenderAdjustments, 1200);
    new MutationObserver(()=>relaxClip())
      .observe(document.getElementById('mm-view'), { childList:true, subtree:true });
  </script>
</body>
</html>

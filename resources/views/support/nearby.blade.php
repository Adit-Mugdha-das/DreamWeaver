<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Nearby Health Support</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  @vite('resources/css/app.css')
  <style>
    :root{
      --bg:#0b0f19; --panel:rgba(255,255,255,.06); --border:rgba(255,255,255,.12);
      --muted:#a0a0b3; --accent:#d966ff; --accent-2:#8b5cf6;
    }
    *{box-sizing:border-box}
    body{margin:0;color:#fff;font-family:Inter,system-ui,sans-serif;min-height:100vh;background:var(--bg)}
    /* dreamy animated background */
    #bg{position:fixed;inset:0;z-index:-2}
    /* subtle light glows */
    #vignette{position:fixed;inset:0;pointer-events:none;z-index:-1;
      background: radial-gradient(1200px 600px at 70% -10%, rgba(216,102,255,.18), transparent 60%),
                  radial-gradient(900px 500px at 10% 30%, rgba(139,92,246,.18), transparent 65%)}
    .wrap{max-width:1200px;margin:0 auto;padding:2rem}
    h1{color:#f0e7ff;text-align:center;margin:0 0 1.1rem;font-weight:800;letter-spacing:.3px}
    .subtitle{color:#bfa8ff;text-align:center;margin-top:-.6rem;margin-bottom:1.2rem;font-size:.95rem}

    /* Toolbar */
    .toolbar{display:flex;flex-wrap:wrap;gap:.8rem;justify-content:center;margin-bottom:1rem}
    .btn{
      background:linear-gradient(135deg, #d966ff, #8b5cf6);border:none;color:#fff;font-weight:700;
      padding:.72rem 1.25rem;border-radius:.9rem;cursor:pointer;
      box-shadow:0 8px 24px rgba(216,102,255,.35);transition:all .25s ease
    }
    .btn:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(216,102,255,.55)}
    .control{position:relative}
    .select,.check,.input{
      background:rgba(255,255,255,.08);border:1px solid var(--border);color:#f5f3ff;border-radius:.7rem;
      padding:.6rem .9rem;min-width:150px;appearance:none;cursor:pointer;font-weight:600;
      box-shadow:inset 0 0 6px rgba(0,0,0,.35)
    }
    .select:focus{outline:none;border-color:#d966ff;box-shadow:0 0 0 2px rgba(216,102,255,.35)}
    .control .chev{position:absolute;right:.6rem;top:50%;transform:translateY(-50%);pointer-events:none;color:#d8c9ff;font-size:.9rem}
    /* (Browser support varies, but helps on many) */
    select option{background:#1a103d;color:#f3e8ff}

    /* Layout */
    .grid{display:grid;grid-template-columns:2fr 1.1fr;gap:1rem}
    @media (max-width:1024px){.grid{grid-template-columns:1fr}}
    .card{background:rgba(20,20,35,.72);border:1px solid var(--border);border-radius:1rem;overflow:hidden;backdrop-filter:blur(8px)}
    #map{height:600px;width:100%}
    .results{max-height:600px;overflow:auto}
    .list-head{position:sticky;top:0;z-index:1;background:linear-gradient(180deg, rgba(10,14,24,.98), rgba(10,14,24,.92));
      padding:.6rem .8rem;border-bottom:1px solid var(--border)}
    .pill{background:rgba(216,102,255,.12);border:1px solid rgba(216,102,255,.35);
      padding:.35rem .6rem;border-radius:.7rem;font-size:.8rem}

    /* Result items */
    .item{display:grid;grid-template-columns:64px 1fr;gap:.8rem;padding:.9rem;border-bottom:1px solid rgba(255,255,255,.08)}
    .item:hover{background:rgba(216,102,255,.08)}
    .thumb{width:64px;height:64px;border-radius:.6rem;object-fit:cover;background:#111;border:1px solid var(--border)}
    .title{color:#f6f3ff;font-weight:800;line-height:1.2}
    .muted{color:var(--muted);font-size:.9rem}
    .badge{display:inline-block;padding:.2rem .55rem;border-radius:.5rem;font-size:.75rem;margin-left:.4rem}
    .badge-open{background:#052e1b;color:#34d399;border:1px solid #065f46}
    .badge-closed{background:#2a0b0b;color:#fda4af;border:1px solid #7f1d1d}
    .stars{letter-spacing:1px}
    .actions{margin-top:.45rem}
    .link{
      display:inline-flex;align-items:center;gap:.35rem;background:rgba(255,255,255,.08);
      border:1px solid var(--border);padding:.45rem .65rem;border-radius:.55rem;color:#e9d5ff;text-decoration:none
    }
    .hi{outline:2px solid var(--accent-2);outline-offset:2px;border-radius:.7rem}
    .loading{padding:1rem;text-align:center;color:var(--muted)}

    /* ---- DARK INFOWINDOW FIX ---- */
    .gm-style .gm-style-iw-c{
      background: rgba(20,20,35,.97) !important;
      color: #e5e7eb !important;
      border-radius: 12px !important;
      box-shadow: 0 12px 30px rgba(0,0,0,.35) !important;
    }
    .gm-style .gm-style-iw-t::after{ background: rgba(20,20,35,.97) !important; }
    .gm-style .gm-style-iw-d .muted{ color:#a3a3b8 !important; }
    .gm-style .gm-style-iw-c .iw-link{
      display:inline-flex;align-items:center;gap:.35rem;
      background:#111827;color:#ffffff !important;border:1px solid #8b5cf6;
      padding:.42rem .65rem;border-radius:.55rem;text-decoration:none
    }
    .gm-style .gm-style-iw-c .iw-link:hover{ background:#1f2937; }

    .home-btn{
  position: fixed;
  top: 14px;
  left: 14px;
  z-index: 9999;
  display: inline-flex;
  align-items: center;
  gap: .5rem;
  padding: .55rem .85rem;
  border-radius: .85rem;
  background: rgba(20,20,35,.78);
  color: #e9d5ff;
  text-decoration: none;
  border: 1px solid rgba(255,255,255,.14);
  backdrop-filter: blur(6px);
  box-shadow: 0 10px 24px rgba(0,0,0,.28);
  font-weight: 700;
}
.home-btn:hover{
  background: rgba(216,102,255,.16);
  border-color: rgba(216,102,255,.45);
  transform: translateY(-1px);
  transition: all .18s ease;
}

  </style>
</head>
<body>
  <!-- dreamy background layers -->
  <div id="bg"></div>
  <div id="vignette"></div>
  <!-- Home button -->
<a href="{{ url('welcome') }}" class="home-btn" aria-label="Go to Home">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
       style="color:#d966ff">
    <path d="M3 11l9-7 9 7" />
    <path d="M9 22V12h6v10" />
  </svg>
  <span>Home</span>
</a>


  <div class="wrap">
    <h1>Find Nearby Health Support</h1>
    <div class="subtitle">Discover hospitals and clinics around you — beautifully organized with distance, ratings and directions.</div>

    <!-- Toolbar -->
    <div class="toolbar">
      <button id="detectBtn" class="btn">Detect &amp; Fetch Support Centers</button>

      <div class="control">
        <select id="category" class="select" title="Category">
          <option value="hospital" selected>All Hospitals</option>
          <option value="doctor">Doctors &amp; Clinics</option>
          <option value="pharmacy">Pharmacies</option>
          <option value="physiotherapist">Physiotherapists</option>
          <option value="dentist">Dentists</option>
          <option value="veterinary_care">Veterinary</option>
        </select>
        <span class="chev">▾</span>
      </div>

      <label class="select" style="display:flex;align-items:center;gap:.55rem">
        <input type="checkbox" id="openNow" style="transform:translateY(1px)"> Open now
      </label>

      <div class="control">
        <select id="sortBy" class="select" title="Sort by">
          <option value="distance" selected>Sort: Distance</option>
          <option value="rating">Sort: Rating</option>
        </select>
        <span class="chev">▾</span>
      </div>

      <div class="control">
        <select id="radius" class="select" title="Search radius (km)">
          <option value="3000">3 km</option>
          <option value="5000">5 km</option>
          <option value="8000">8 km</option>
          <option value="10000" selected>10 km</option>
          <option value="15000">15 km</option>
        </select>
        <span class="chev">▾</span>
      </div>
    </div>

    <!-- Map + Results -->
    <div class="grid">
      <div class="card"><div id="map"></div></div>
      <div class="card results" id="results">
        <div class="list-head">
          <span class="pill" id="summary">Results</span>
        </div>
        <div id="list"></div>
      </div>
    </div>
  </div>

  <!-- Marker clustering -->
  <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
  <!-- three.js + Vanta.NET for dreamy background -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta/dist/vanta.net.min.js"></script>

  <script>
    // === Dreamy background ===
    VANTA.NET({
      el: "#bg",
      mouseControls: true,
      touchControls: true,
      gyroControls: false,
      minHeight: 200.00,
      minWidth: 200.00,
      scale: 1.0,
      scaleMobile: 1.0,
      color: 0xd966ff,
      backgroundColor: 0x0b0f19,
      points: 12.00,
      maxDistance: 20.00,
      spacing: 18.00
    });

    // === Map + Places logic ===
    let map, info, userMarker, placesService, markers = [], clusterer;
    let userPos = null;

    const el = (id)=>document.getElementById(id);
    const km = (m)=> (m/1000).toFixed(1);

    function initMap(center){
      map = new google.maps.Map(document.getElementById('map'), { center, zoom: 13, mapId: 'DW_MAP' });
      info = new google.maps.InfoWindow();
      userMarker = new google.maps.Marker({
        position:center, map, title:'You are here',
        icon:{ path: google.maps.SymbolPath.CIRCLE, scale: 8, fillColor:'#00f0ff', fillOpacity:1, strokeWeight:2, strokeColor:'#fff' }
      });
      placesService = new google.maps.places.PlacesService(map);
    }

    function clearMarkers(){
      markers.forEach(m=>m.setMap(null));
      markers = [];
      if (clusterer) clusterer.clearMarkers();
    }

    function photoUrl(place){
      const p = place.photos?.[0];
      return p ? p.getUrl({maxWidth: 400, maxHeight: 400}) : '';
    }

    function stars(r){
      if(!r) return '';
      const full = '★'.repeat(Math.round(r));
      const empty = '☆'.repeat(5-Math.round(r));
      return `<span class="stars">${full}${empty}</span> <span class="muted">(${r.toFixed(1)})</span>`;
    }

    function haversine(a, b){
      const R = 6371000; // m
      const toRad = d => d*Math.PI/180;
      const dLat = toRad(b.lat - a.lat), dLng = toRad(b.lng - a.lng);
      const s = Math.sin(dLat/2)**2 + Math.cos(toRad(a.lat))*Math.cos(toRad(b.lat))*sin2(dLng/2);
      return 2*R*Math.asin(Math.sqrt(s));
      function sin2(x){const s=Math.sin(x);return s*s;}
    }

    function renderList(places){
      const list = el('list');
      if(!places?.length){ list.innerHTML = `<div class="loading">No results in this area.</div>`; el('summary').textContent = '0 results'; return; }
      el('summary').textContent = `${places.length} result${places.length>1?'s':''}`;
      list.innerHTML = places.map((p,i)=> {
        const addr = p.vicinity || p.formatted_address || '';
        const open = p.opening_hours?.open_now;
        const dist = userPos ? haversine(userPos, {lat:p.geometry.location.lat(), lng:p.geometry.location.lng()}) : null;
        return `
        <div class="item" data-idx="${i}">
          <img class="thumb" src="${photoUrl(p) || 'https://maps.gstatic.com/mapfiles/place_api/icons/v1/png_71/hospital-71.png'}" alt="">
          <div>
            <div class="title">${i+1}. ${p.name}</div>
            <div class="muted">${addr}</div>
            <div class="muted" style="margin-top:.2rem;">
              ${stars(p.rating)} ${p.user_ratings_total?`· <span class="muted">${p.user_ratings_total} reviews</span>`:''}
              ${dist!==null?`· <span class="muted">${km(dist)} km</span>`:''}
              ${open===true?`<span class="badge badge-open">Open now</span>`:open===false?`<span class="badge badge-closed">Closed</span>`:''}
            </div>
            <div class="actions">
              <a class="link" target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(p.name)}&destination_place_id=${p.place_id}">
                Directions
              </a>
            </div>
          </div>
        </div>`;
      }).join('');

      // hover → highlight marker
      list.querySelectorAll('.item').forEach(card => {
        card.addEventListener('mouseenter', () => {
          const idx = Number(card.dataset.idx);
          markers[idx]?.setAnimation(google.maps.Animation.BOUNCE);
          card.classList.add('hi');
        });
        card.addEventListener('mouseleave', () => {
          const idx = Number(card.dataset.idx);
          markers[idx]?.setAnimation(null);
          card.classList.remove('hi');
        });
        card.addEventListener('click', () => {
          const idx = Number(card.dataset.idx);
          google.maps.event.trigger(markers[idx], 'click');
          map.panTo(markers[idx].getPosition());
        });
      });
    }

    function addMarkers(places){
      clearMarkers();
      markers = places.map((p,i)=>{
        const m = new google.maps.Marker({
          position: p.geometry.location, map, label: String(i+1), title: p.name
        });
        m.addListener('click', ()=>{
          info.setContent(`
            <div style="max-width:260px">
              <b>${p.name}</b><br>
              <div class="muted">${p.vicinity || p.formatted_address || ''}</div>
              ${p.rating?`<div class="muted" style="margin-top:.2rem">${stars(p.rating)} · ${p.user_ratings_total||0} reviews</div>`:''}
              <div style="margin-top:.45rem">
                <a class="iw-link" target="_blank" href="https://www.google.com/maps/place/?q=place_id:${p.place_id}">
                  Open in Google Maps
                </a>
              </div>
            </div>`);
          info.open(map,m);
        });
        return m;
      });
      clusterer = new markerClusterer.MarkerClusterer({ map, markers });
    }

    function doSearch(center){
      const type   = el('category').value;
      const radius = Number(el('radius').value);
      const openNow= el('openNow').checked;
      const sortBy = el('sortBy').value;

      el('list').innerHTML = `<div class="loading">Searching ${type.replace('_',' ')} within ${(radius/1000)} km…</div>`;

      const req = { location:center, radius, type };
      if(openNow) req.openNow = true;

      placesService.nearbySearch(req, (results, status) => {
        if(status !== google.maps.places.PlacesServiceStatus.OK || !results){
          renderList([]); return;
        }

        // Compute distance for sorting
        results.forEach(p=>{
          p._dist = userPos ? haversine(userPos, {lat:p.geometry.location.lat(), lng:p.geometry.location.lng()}) : Infinity;
        });

        if (sortBy === 'rating') {
          results.sort((a,b)=> (b.rating||0) - (a.rating||0));
        } else {
          results.sort((a,b)=> a._dist - b._dist);
        }

        addMarkers(results);
        renderList(results);
      });
    }

    // Detect button
    document.getElementById('detectBtn').addEventListener('click', () => {
      if(!navigator.geolocation){ alert('Geolocation not supported'); return; }
      navigator.geolocation.getCurrentPosition(pos=>{
        userPos = { lat: pos.coords.latitude, lng: pos.coords.longitude };
        if(!map) initMap(userPos); else { map.setCenter(userPos); userMarker?.setPosition(userPos); }
        doSearch(userPos);
      }, err=>{
        // Dhaka fallback
        userPos = { lat: 23.8103, lng: 90.4125 };
        if(!map) initMap(userPos); else { map.setCenter(userPos); userMarker?.setPosition(userPos); }
        doSearch(userPos);
      }, { enableHighAccuracy:true, timeout: 10000 });
    });

    // Controls re-run search
    ['category','openNow','sortBy','radius'].forEach(id=>{
      document.getElementById(id).addEventListener('change', ()=>{ if(userPos) doSearch(userPos); });
    });
  </script>

  <!-- Google Maps JS with Places -->
  <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places" async defer></script>
</body>
</html>

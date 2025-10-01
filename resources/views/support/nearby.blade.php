<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nearby Mental Health Support</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <style>
    body{background:#0b0f19;color:#fff;font-family:Inter,system-ui,sans-serif;margin:0}
    .wrap{max-width:1100px;margin:0 auto;padding:2rem}
    h1{color:#d966ff;text-align:center;margin-bottom:1rem}
    .btn{background:#d966ff;border:1px solid #f0abfc;color:#fff;font-weight:600;
         padding:.7rem 1.2rem;border-radius:.75rem;cursor:pointer;display:block;margin:0 auto 1rem}
    .btn:hover{filter:brightness(1.07)}
    .card{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);
          border-radius:1rem;overflow:hidden}
    #map{height:520px;width:100%}
    .results{max-height:520px;overflow:auto}
    .row{display:grid;grid-template-columns:2fr 1fr;gap:1rem}
    @media (max-width: 900px){ .row{grid-template-columns:1fr}}
    .item{padding:.8rem;border-bottom:1px solid rgba(255,255,255,.08)}
    .item b{color:#a78bfa}
    .muted{color:#a0a0b3;font-size:.9rem}
  </style>
</head>
<body>
  <div class="wrap">
    <h1>Find Nearby Mental Health Support</h1>
    <button id="detectBtn" class="btn">Detect & Fetch Support Centers</button>

    <div class="row">
      <div class="card"><div id="map"></div></div>
      <div class="card results" id="results"></div>
    </div>
  </div>

  <script>
    let map, service, info, userMarker;

    function initCoreMap(center){
      map = new google.maps.Map(document.getElementById('map'), {
        center, zoom: 14, mapId: 'DEMO_MAP'
      });
      info = new google.maps.InfoWindow();
      userMarker = new google.maps.Marker({
        position: center, map, title: 'You are here', icon: {
          path: google.maps.SymbolPath.CIRCLE, scale: 8, fillColor: '#00f0ff',
          fillOpacity: 1, strokeWeight: 2, strokeColor: '#fff'
        }
      });
      service = new google.maps.places.PlacesService(map);
    }

    function addPlaceMarker(place){
      const marker = new google.maps.Marker({
        map, position: place.geometry.location, title: place.name
      });
      marker.addListener('click', () => {
        info.setContent(
          `<div style="max-width:260px">
             <b>${place.name}</b><br>
             <span class="muted">${place.vicinity || place.formatted_address || ''}</span><br>
             ${place.rating ? `⭐ ${place.rating} (${place.user_ratings_total || 0})` : ''}
           </div>`
        );
        info.open(map, marker);
      });
    }

    function renderList(places, status){
      const wrap = document.getElementById('results');
      if(!places?.length){
        wrap.innerHTML = `<div class="item">No results found nearby.<br>Status: ${status}</div>`;
        return;
      }
      wrap.innerHTML = places.map(p => `
        <div class="item">
          <b>${p.name}</b><br>
          <div class="muted">${p.vicinity || p.formatted_address || ''}</div>
          ${p.rating ? `<div class="muted">⭐ ${p.rating} · ${p.user_ratings_total || 0} reviews</div>` : ''}
        </div>
      `).join('');
    }

    function searchNearby(center){
      const request = {
        location: center,
        radius: 10000, // 10km
        type: 'hospital', // ensures hospital category
        keyword: 'mental health, psychiatrist, psychologist, counseling, clinic'
      };
      service.nearbySearch(request, (results, status) => {
        if(status !== google.maps.places.PlacesServiceStatus.OK){
          renderList([], status);
          return;
        }
        results.forEach(addPlaceMarker);
        renderList(results, status);
      });
    }

    document.getElementById('detectBtn').addEventListener('click', () => {
      if(!navigator.geolocation){ alert('Geolocation not supported.'); return; }
      navigator.geolocation.getCurrentPosition(
        pos => {
          const center = { lat: pos.coords.latitude, lng: pos.coords.longitude };
          if(!map) initCoreMap(center); else map.setCenter(center);
          userMarker?.setPosition(center);
          searchNearby(center);
        },
        err => {
          // fallback location (e.g., Dhaka center)
          const center = { lat: 23.8103, lng: 90.4125 };
          if(!map) initCoreMap(center); else map.setCenter(center);
          searchNearby(center);
        },
        { enableHighAccuracy: true, timeout: 10000 }
      );
    });
  </script>

  <!-- Load Maps JS API with Places library -->
  <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places" async defer></script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nearby Support</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
</head>
<body class="bg-black text-white">
  <div class="p-6 text-center">
    <h1 class="text-3xl text-fuchsia-400 font-bold mb-4">Find Nearby Mental Health Support</h1>
    <button onclick="findNearby()" class="px-6 py-2 bg-fuchsia-600 hover:bg-fuchsia-700 rounded">
      📍 Detect & Fetch Support Centers
    </button>

    <div id="map" class="w-full h-96 mt-6 rounded border border-fuchsia-500"></div>

    <div id="results" class="mt-6 space-y-4"></div>
  </div>

  <script>
    function findNearby() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
          const lat = pos.coords.latitude;
          const lng = pos.coords.longitude;

          // Display Map
          const map = new google.maps.Map(document.getElementById("map"), {
            center: { lat, lng },
            zoom: 13,
            mapId: "dreamweaver-map"
          });

          // Marker for User
          new google.maps.Marker({
            position: { lat, lng },
            map,
            title: "You are here",
            icon: {
              path: google.maps.SymbolPath.CIRCLE,
              scale: 7,
              fillColor: "#00f",
              fillOpacity: 0.8,
              strokeWeight: 2,
              strokeColor: "#fff"
            }
          });

          // Fetch hospitals/doctors
          fetch("/get-nearby", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ latitude: lat, longitude: lng })
          })
          .then(res => res.json())
          .then(data => {
            const resultBox = document.getElementById("results");
            resultBox.innerHTML = "";

            data.forEach(place => {
              // Add marker
              if (place.geometry?.location) {
                const marker = new google.maps.Marker({
                  position: place.geometry.location,
                  map,
                  title: place.name
                });

                const infowindow = new google.maps.InfoWindow({
                  content: `<strong>${place.name}</strong><br>${place.vicinity}`
                });

                marker.addListener("click", () => infowindow.open(map, marker));
              }

              // Show list item
              resultBox.innerHTML += `
                <div class="p-4 bg-black/60 border border-fuchsia-400/20 rounded">
                  <h2 class="text-lg text-fuchsia-400">${place.name}</h2>
                  <p>${place.vicinity}</p>
                  <p class="text-sm">Rating: ${place.rating ?? 'N/A'}</p>
                </div>
              `;
            });
          });
        });
      }
    }
  </script>
</body>
</html>

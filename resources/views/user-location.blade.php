<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Get Current Location with Leaflet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Masukkan stylesheet Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <!-- CSS khusus Anda (opsional) -->
  <style>
    /* Atur ukuran peta */
    #map {
      height: 400px;
      width: 100%;
    }
  </style>
</head>
<body>
  <!-- Buat wadah untuk peta Leaflet -->
  <div id="map"></div>

  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <script>
    // Inisialisasi peta
    var map = L.map('map').setView([0, 0], 15); // Koordinat awal

    // Tambahkan layer peta dari OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Tambahkan marker lokasi saat ini
    var marker = L.marker([0, 0], {
      draggable: true, // Agar marker dapat digeser
    }).addTo(map);

    // Fungsi untuk mendapatkan lokasi saat ini
    function getCurrentLocation() {
      if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition(function (position) {
          var lat = position.coords.latitude;
          var lon = position.coords.longitude;

          // Update marker ke lokasi saat ini
          marker.setLatLng([lat, lon]);
          map.setView([lat, lon], 15);
        });
      } else {
        alert('Geolocation tidak didukung di peramban ini.');
      }
    }

    // Panggil fungsi getCurrentLocation() saat halaman dimuat
    getCurrentLocation();

    // Tambahkan tombol untuk mendapatkan lokasi saat ini
    var locationButton = L.control({position: 'topright'});
    locationButton.onAdd = function () {
      var div = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
      div.innerHTML = '<a href="#" onclick="getCurrentLocation()">Get Location</a>';
      return div;
    };
    locationButton.addTo(map);
  </script>
</body>
</html>

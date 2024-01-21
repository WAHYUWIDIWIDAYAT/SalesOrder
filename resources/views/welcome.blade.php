<!DOCTYPE html>
<html>
<head>
    <title>Map with Route</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        #map {
            width: 100%;
            height: 60vh;
        }
    </style>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
</head>
<body>
    <div id="map"></div>

    <script>
        var map = L.map('map');

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var pointA = L.latLng(-6.98269795, 110.40902661344302);
        var pointB = L.latLng(-6.95, 110.40);

        L.marker(pointA).addTo(map).bindPopup('Point A');
        L.marker(pointB).addTo(map).bindPopup('Point B');

        var waypoints = [
            L.latLng(pointA),
            L.latLng(pointB)
        ];

        var routeControl = L.Routing.control({
            waypoints: waypoints,
            lineOptions: {
                styles: [{color: 'blue', opacity: 1, weight: 5}]
            }
        }).addTo(map);

        // Use Geolocation API to get the user's location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var userLatLng = L.latLng(position.coords.latitude, position.coords.longitude);

                // Add a marker for the user's location
                L.marker(userLatLng).addTo(map).bindPopup('Your Location').openPopup();

                // Update the map's view to include the user's location
                map.setView(userLatLng, 13);
            });
        }

        var bounds = L.latLngBounds(waypoints);
        map.fitBounds(bounds);
    </script>
</body>
</html>
<!-- 

<!DOCTYPE html>
<html>
<head>
    <title>Map with Route</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        #map {
            width: 100%;
            height: 60vh;
        }
    </style>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
</head>
<body>
    <div id="map"></div>

    <script>
        var map = L.map('map');

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

       
      

        L.marker(pointA).addTo(map).bindPopup('Point A');
        L.marker(pointB).addTo(map).bindPopup('Point B');

        var waypoints = [
            L.latLng(pointA),
            L.latLng(pointB)

        ];

        var routeControl = L.Routing.control({
            waypoints: waypoints,
            lineOptions: {
                styles: [{color: 'blue', opacity: 1, weight: 5}]
            }

            // Add a marker for the point A and B
           
        }).addTo(map);

        // Use Geolocation API to get the user's location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var userLatLng = L.latLng(position.coords.latitude, position.coords.longitude);

                // Add a marker for the user's location
                L.marker(userLatLng).addTo(map).bindPopup('Your Location').openPopup();
                L.marker(pointA).addTo(map).bindPopup('Origin');
               L.marker(pointB).addTo(map).bindPopup('Destination');

                // Update the map's view to include the user's location
                map.setView(userLatLng, 13);
            });
        }

        var bounds = L.latLngBounds(waypoints);
        map.fitBounds(bounds);
    </script>
</body>
</html> -->

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map Click Example</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
</head>
<body>
    <div id="map" style="width: 100%; height: 500px;"></div>
    <div>
        <label for="destination-lat">Destination Latitude:</label>
        <input type="text" id="destination-lat" readonly>
        <label for="destination-lng">Destination Longitude:</label>
        <input type="text" id="destination-lng" readonly>
        <label for="destination-address">Destination Address:</label>
        <input type="text" id="destination-address" readonly>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder@1.13.0/dist/Control.Geocoder.js"></script>

    <script>
        // Initialize the map
        const map = L.map('map').setView([-6.9912, 110.4216], 13);

        // Add a tile layer (you can choose a different tile layer)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Initialize the geocoder control
        const geocoder = L.Control.Geocoder.nominatim();

        // Initialize destination marker and coordinates
        let destinationMarker = null;
        let destinationCoordinates = null;

        // Function to update the address and display it in the input field
        function updateAddress(address, addressElementId) {
            const addressInput = document.getElementById(addressElementId);
            addressInput.value = address || '';
        }

        // Function to handle map click and update destination inputs
        map.on('click', function (e) {
            // Update destination coordinates input fields
            document.getElementById('destination-lat').value = e.latlng.lat.toFixed(6);
            document.getElementById('destination-lng').value = e.latlng.lng.toFixed(6);

            // Get the address for the destination
            geocoder.reverse(e.latlng, map.options.crs.scale(map.getZoom()), (results) => {
                const destinationAddress = results[0] ? results[0].name : 'Address not found';
                updateAddress(destinationAddress, 'destination-address');
            });

            if (destinationMarker) {
                // If the destination marker exists, move it to the new location
                destinationMarker.setLatLng(e.latlng);
            } else {
                // Create the destination marker if it doesn't exist
                destinationCoordinates = e.latlng;
                destinationMarker = L.marker(destinationCoordinates, { draggable: true }).addTo(map);
                destinationMarker.bindTooltip('Destination', { permanent: true, className: 'custom-tooltip' }).openTooltip();

                // Listen to the marker's dragend event to update the coordinates
                destinationMarker.on('dragend', function (e) {
                    destinationCoordinates = e.target.getLatLng();
                    document.getElementById('destination-lat').value = destinationCoordinates.lat.toFixed(6);
                    document.getElementById('destination-lng').value = destinationCoordinates.lng.toFixed(6);

                    // Get the updated address for the destination
                    geocoder.reverse(destinationCoordinates, map.options.crs.scale(map.getZoom()), (results) => {
                        const destinationAddress = results[0] ? results[0].name : 'Address not found';
                        updateAddress(destinationAddress, 'destination-address');
                    });
                });
            }
        });
    </script>
</body>
</html> -->
@extends('layouts.admin')
@section('title')
    <title>Tambah Task</title>
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
<div class="card">
        <div style="max-width: 100%; width: 100%; height: 500px;">
            <div id="display-google-map" style="height: 100%; width: 100%; max-width: 100%;">
            <iframe id="map-iframe" style="height:100%;width:100%;border:0;" frameborder="0"></iframe>

            </div>
        </div>
        <style>
            #display-google-map img.text-marker {
                max-width: none!important;
                background: none!important;
            }
            img {
                max-width: none;
            }
        </style>
    </div>
</div>
<br><br>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Include Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            $('#file-input').change(function(e) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            if ($('#preview-image').attr('src') == '') {    
                $('#preview-image').attr('src', '{{ asset('admin/assets/img/backgrounds/no-image.jpg') }}').attr('width', '185').attr('height', '185');
            }
        });
    </script>
    <script>
        CKEDITOR.replace('task_description');
    </script>
    <script>
    $(document).ready(function () {
        $('#province_id').on('change', function () {
            var province_id = $(this).val();
            if (province_id) {
                $.ajax({
                    url: '/api/city', 
                    type: "GET",
                    data: {
                        "province_id": province_id
                    },
                    success: function (data) {
                        $('#city_id').empty();
                        $.each(data.cities, function (key, value) {
                            $('#city_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.log('Error:', error);
                    }
                });
            } else {
                $('#city_id').empty();
            }
        });
    });
</script>
<script>
    // Get the elements you want to use in JavaScript
    

    

    // Set the destination in the Google Maps iframe
    var iframe = document.getElementById('map-iframe');
    const originLat = -6.9912;
    const originLng = 110.4216;
    const destinationLat = -7.019231;
    const destinationLng =  110.448625;

    // Encode the coordinates for the destination
    const encodedDestination = encodeURIComponent(`${destinationLat},${destinationLng}`);

    // Construct the Google Maps iframe URL with latitude and longitude coordinates
    const iframeSrc = `https://www.google.com/maps/embed/v1/directions?origin=${originLat},${originLng}&destination=${encodedDestination}&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8`;

    // Set the iframe source
    iframe.src = iframeSrc;

</script>
<script>
        $(document).ready(function () {
            $('#city_id').on('change', function () {
                var city_id = $(this).val();
                if (city_id) {
                    $.ajax({
                        url: '/api/district',
                        type: "GET",
                        data: {
                            "city_id": city_id
                        },
                        success: function (data) {
                            $('#district_id').empty();
                            $.each(data.districts, function (key, value) {
                                $('#district_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                        },
                    });
                } else {
                    $('#district_id').empty();
                }
            });
        });
    </script>
<script>
    
@endsection


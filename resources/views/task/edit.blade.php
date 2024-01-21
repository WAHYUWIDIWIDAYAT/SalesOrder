@extends('layouts.admin')
@section('title')
    <title>Edit Task</title>
    
@endsection

@section('content')
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
/>
<script
    src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script
    src="https://unpkg.com/leaflet-control-geocoder@1.13.0/dist/Control.Geocoder.js"
></script>
<style>
    #map {
        width: 100%;
        height: 500px;
    }
    .coordinates {
        margin-top: 10px;
        font-weight: bold;
    }
    .address {
        margin-top: 10px;
    }
</style>
<div class="content-wrapper">

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Task /</span> Edit</h4>
    <form action="{{ route('task.update', $task->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6">
        <div class="card mb-4">
            <h5 class="card-header">Edit Task</h5>
            <div class="card-body demo-vertical-spacing demo-only-element">
                <div class="mb-3">
                <label class="form-label" for="basic-default-name">Name Task</label>
                <input
                    type="text"
                    class="form-control"
                    id="task_name"
                    name="task_name"
                    placeholder="Enter Name Task"
                    value="{{ $task->task_name }}"
                />
                <p class="text-danger">{{ $errors->first('task_name') }}</p>
                </div>
                <div class="mb-3">
                <label class="form-label" for="basic-default-description">Description Task</label>
                <textarea
                    class="form-control"
                    id="task_description"
                    name="task_description"
      
                    placeholder="Enter Description Task"
                    required
                >{{ $task->task_description }}</textarea>
                <p class="text-danger">{{ $errors->first('task_description') }}</p>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                    <select class="form-select" id="user_id" name="user_id" required>
                    <option value="{{ $task->user_id }} {{ $task->origin_longitude }} {{ $task->origin_latitude }}">{{ $task->user->name }}</option>
                    @foreach ($users as $row)
                    <option value="{{ $row->id }} {{ $row->longitude }} {{ $row->latitude }}">{{ $row->name }}</option>
                    @endforeach
                    </select>
                    <p class="text-danger">{{ $errors->first('user_id') }}</p>
                    <label class="input-group-text" for="sales">Sales</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                    <select class="form-select" id="customer_id" name="customer_id" required>
                    <option value="{{ $task->customer_id }}">{{ $task->customer->name }}</option>
                    @foreach ($customers as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                    </select>
                    <p class="text-danger">{{ $errors->first('customer_id') }}</p>
                    <label class="input-group-text" for="customer">Customer</label>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="col-md-6">
        <div class="card mb-4">
            <h5 class="card-header">Address</h5>
            <div class="card-body demo-vertical-spacing demo-only-element">
            <div class="mb-3">
            <br>
            <div class="mb-3">
     
<div class="mb-3">
    <label class="form-label" for="basic-default-name">Destination Address</label>
    <input
        type="text"
        class="form-control"
        name="destination_address"
        placeholder="Enter Address"
        value="{{ $task->destination_address }}"
        required
        readonly
        id="destination-address"
    />
</div>

<div class="mb-3">
    <label class="form-label" for="basic-default-name">Latitude Destination</label>
    <input
        type="text"
        class="form-control"
        name="destination_latitude"
        placeholder="Enter Address"
        required
        value="{{ $task->destination_latitude }}"
        readonly
        id="destination-lat"
    />
</div>
<div class="mb-3">
    <label class="form-label" for="basic-default-name">Longitude Destination</label>
    <input
        type="text"
        class="form-control"
        name="destination_longitude"
        placeholder="Enter Address"
        value="{{ $task->destination_longitude }}"
        required
        readonly
        id="destination-lng"
    />
</div>
        </div>
        </div>
    </div>
    </div>
    <div class="row">
        <div class="col-md-6">
        </div>
        <div class="mb-3">
            <div class="card mb-4">
                <h5 class="card-header">Action Button</h5>
                <br>
                <div class="card-body demo-vertical-spacing demo-only-element">
                <center>
                <button type="submit" class="btn btn-primary">Edit</button>
              
                <button type="button" class="btn btn-danger" onclick="window.history.back()">Kembali</button>
                </center>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    </form>
</div>
<div class="content-wrapper">

<div id="map"></div>

</div>
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
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder@1.13.0/dist/Control.Geocoder.js"></script>

    <script>
        // Initialize the map
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

// Initialize values from destination inputs
const destinationLatInput = document.getElementById('destination-lat');
const destinationLngInput = document.getElementById('destination-lng');
const destinationAddressInput = document.getElementById('destination-address');

// Function to update the address and display it in the input field
function updateAddress(address, addressElement) {
    if (addressElement) {
        addressElement.value = address || '';
    }
}

// Function to create or update the destination marker
function updateDestinationMarker(coordinates) {
    if (destinationMarker) {
        destinationMarker.setLatLng(coordinates);
    } else {
        destinationMarker = L.marker(coordinates, { draggable: true }).addTo(map);
        destinationMarker.bindTooltip('Destination', { permanent: true, className: 'custom-tooltip' }).openTooltip();

        // Listen to the marker's dragend event to update the coordinates
        destinationMarker.on('dragend', function (e) {
            const updatedCoordinates = e.target.getLatLng();
            destinationLatInput.value = updatedCoordinates.lat.toFixed(6);
            destinationLngInput.value = updatedCoordinates.lng.toFixed(6);

            // Get the updated address for the destination
            geocoder.reverse(updatedCoordinates, map.options.crs.scale(map.getZoom()), (results) => {
                if (results && results.length > 0) {
                    const updatedAddress = results[0].name;
                    updateAddress(updatedAddress, destinationAddressInput);
                } else {
                    console.error('Address not found for coordinates:', updatedCoordinates);
                }
            });
        });
    }
}

// Function to fetch the initial address when the page loads
function fetchInitialAddress(initialCoordinates) {
    geocoder.reverse(initialCoordinates, map.options.crs.scale(map.getZoom()), (results) => {
        if (results && results.length > 0) {
            const initialAddress = results[0].name;
            updateAddress(initialAddress, destinationAddressInput);
        } else {
            console.error('Initial Address not found for coordinates:', initialCoordinates);
        }
    });
}

// Set initial values from destination inputs
const initialLat = parseFloat(destinationLatInput.value);
const initialLng = parseFloat(destinationLngInput.value);
const initialCoordinates = [initialLat, initialLng];
updateDestinationMarker(initialCoordinates);

// Fetch the initial address when the page loads
fetchInitialAddress(initialCoordinates);

// Handle map click to update destination
map.on('click', function (e) {
    destinationCoordinates = e.latlng;
    destinationLatInput.value = destinationCoordinates.lat.toFixed(6);
    destinationLngInput.value = destinationCoordinates.lng.toFixed(6);

    // Get the updated address for the destination
    geocoder.reverse(destinationCoordinates, map.options.crs.scale(map.getZoom()), (results) => {
        if (results && results.length > 0) {
            const updatedAddress = results[0].name;
            updateAddress(updatedAddress, destinationAddressInput);
        } else {
            console.error('Address not found for coordinates:', destinationCoordinates);
        }
    });//

    // Update the destination marker
    updateDestinationMarker(destinationCoordinates);
});

</script>
<script>

//     // Initialize the map
// const map = L.map('map').setView([-6.9912, 110.4216], 13);

// // Add a tile layer (you can choose a different tile layer)
// L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
// }).addTo(map);

// // Initialize the geocoder control
// const geocoder = L.Control.Geocoder.nominatim();

// // Initialize markers for origin and destination
// let originMarker = null;
// let destinationMarker = null;

// // Initialize coordinates for origin and destination
// let originCoordinates = null;
// let destinationCoordinates = null;

// // Initialize addresses for origin and destination
// let originAddress = '';
// let destinationAddress = '';

// function setInputValue(elementId, value) {
//     const inputElement = document.getElementById(elementId);
//     if (inputElement) {
//         inputElement.value = value;
//     }
// }

// // Default coordinates for origin and destination    
// let defaultOriginCoordinates = L.latLng({{$task->origin_latitude}}, {{$task->origin_longitude}});
// let defaultDestinationCoordinates = L.latLng({{$task->destination_latitude}}, {{$task->destination_longitude}});

// // Create the default origin marker
// originMarker = L.marker(defaultOriginCoordinates, { draggable: true }).addTo(map);
// updateCoordinates(defaultOriginCoordinates, 'origin-lat', 'origin-lng');
// originMarker.bindTooltip('Origin', { permanent: true, className: 'custom-tooltip' }).openTooltip();

// // Create the default destination marker
// destinationMarker = L.marker(defaultDestinationCoordinates, { draggable: true }).addTo(map);
// updateCoordinates(defaultDestinationCoordinates, 'destination-lat', 'destination-lng');
// destinationMarker.bindTooltip('Destination', { permanent: true, className: 'custom-tooltip' }).openTooltip();

// // Get the default address for the origin
// geocoder.reverse(defaultOriginCoordinates, map.options.crs.scale(map.getZoom()), (results) => {
//     originAddress = results[0] ? results[0].name : 'Address not found';
//     updateAddress(originAddress, 'origin-address');
// });

// // Get the default address for the destination
// geocoder.reverse(defaultDestinationCoordinates, map.options.crs.scale(map.getZoom()), (results) => {
//     destinationAddress = results[0] ? results[0].name : 'Address not found';
//     updateAddress(destinationAddress, 'destination-address');
// });

// // Listen to the marker's dragend event to update the coordinates for default markers
// originMarker.on('dragend', function (e) {
//     originCoordinates = e.target.getLatLng();
//     updateCoordinates(originCoordinates, 'origin-lat', 'origin-lng');

//     // Get the updated address for the origin
//     geocoder.reverse(originCoordinates, map.options.crs.scale(map.getZoom()), (results) => {
//         originAddress = results[0] ? results[0].name : 'Address not found';
//         updateAddress(originAddress, 'origin-address');
//     });
// });

// // Listen to the marker's dragend event to update the coordinates and address for the destination marker
// destinationMarker.on('dragend', function (e) {
//     destinationCoordinates = e.target.getLatLng();
//     updateCoordinates(destinationCoordinates, 'destination-lat', 'destination-lng');

//     // Get the updated address for the destination
//     geocoder.reverse(destinationCoordinates, map.options.crs.scale(map.getZoom()), (results) => {
//         destinationAddress = results[0] ? results[0].name : 'Address not found';
//         updateAddress(destinationAddress, 'destination-address');
//     });
// });

// // Function to update the coordinates and display them in input text fields
// function updateCoordinates(latlng, latElementId, lngElementId) {
//     const latInput = document.getElementById(latElementId);
//     const lngInput = document.getElementById(lngElementId);

//     latInput.value = latlng ? latlng.lat.toFixed(6) : '';
//     lngInput.value = latlng ? latlng.lng.toFixed(6) : '';
// }

// // Function to update the address and display it in input text fields
// function updateAddress(address, addressElementId) {
//     const addressInput = document.getElementById(addressElementId);

//     // Check if the address is not null or an empty string before updating the input field
//     if (address !== null && address.trim() !== '') {
//         addressInput.value = address;
//     } else {
//         // If address is null or empty, set a default value or handle it as needed
//         addressInput.value = 'Address not found'; // You can change this to any default message
//     }
// }

// map.on('click', function (e) {
//     if (!originMarker) {
//         // Create the origin marker if it doesn't exist
//         originCoordinates = e.latlng;
//         originMarker = L.marker(originCoordinates, { draggable: true }).addTo(map);
//         updateCoordinates(originCoordinates, 'origin-lat', 'origin-lng');
//         originMarker.bindTooltip('Origin', { permanent: true, className: 'custom-tooltip' }).openTooltip();

//         // Get the address for the origin
//         geocoder.reverse(e.latlng, map.options.crs.scale(map.getZoom()), (results) => {
//             originAddress = results[0] ? results[0].name : 'Address not found';
//             updateAddress(originAddress, 'origin-address');
//         });

//         // Listen to the marker's dragend event to update the coordinates
//         originMarker.on('dragend', function (e) {
//             originCoordinates = e.target.getLatLng();
//             updateCoordinates(originCoordinates, 'origin-lat', 'origin-lng');

//             // Get the updated address for the origin
//             geocoder.reverse(originCoordinates, map.options.crs.scale(map.getZoom()), (results) => {
//                 originAddress = results[0] ? results[0].name : 'Address not found';
//                 updateAddress(originAddress, 'origin-address');
//             });
//         });
//     } else if (!destinationMarker) {
//         // Create the destination marker if origin marker exists but destination doesn't
//         destinationCoordinates = e.latlng;
//         destinationMarker = L.marker(destinationCoordinates, { draggable: true }).addTo(map);
//         updateCoordinates(destinationCoordinates, 'destination-lat', 'destination-lng');
//         destinationMarker.bindTooltip('Destination', { permanent: true, className: 'custom-tooltip' }).openTooltip();

//         // Get the address for the destination
//         geocoder.reverse(e.latlng, map.options.crs.scale(map.getZoom()), (results) => {
//             destinationAddress = results[0] ? results[0].name : 'Address not found';
//             updateAddress(destinationAddress, 'destination-address');
//         });

//         // Listen to the marker's dragend event to update the coordinates
//         destinationMarker.on('dragend', function (e) {
//             destinationCoordinates = e.target.getLatLng();
//             updateCoordinates(destinationCoordinates, 'destination-lat', 'destination-lng');

//             // Get the updated address for the destination
//             geocoder.reverse(destinationCoordinates, map.options.crs.scale(map.getZoom()), (results) => {
//                 destinationAddress = results[0] ? results[0].name : 'Address not found';
//                 updateAddress(destinationAddress, 'destination-address');
//             });
//         });
//     } else {
//         // Remove both markers if both markers exist
//         map.removeLayer(originMarker);
//         map.removeLayer(destinationMarker);
//         originMarker = null;
//         destinationMarker = null;

//         // Reset the coordinates display for both origin and destination
//         updateCoordinates(null, 'origin-lat', 'origin-lng');
//         updateCoordinates(null, 'destination-lat', 'destination-lng');

//         // Reset the address display
//         updateAddress('', 'origin-address');
//         updateAddress('', 'destination-address');
//     }
// });

// </script>

@endsection


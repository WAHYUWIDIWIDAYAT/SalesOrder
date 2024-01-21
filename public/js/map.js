// Initialize the map
const map = L.map('map').setView([-6.9912, 110.4216], 13);

// Add a tile layer (you can choose a different tile layer)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Initialize the geocoder control
const geocoder = L.Control.Geocoder.nominatim();

// Initialize markers for origin and destination
let originMarker = null;
let destinationMarker = null;

// Initialize coordinates for origin and destination
let originCoordinates = null;
let destinationCoordinates = null;

// Initialize addresses for origin and destination
let originAddress = '';
let destinationAddress = '';

// Function to set the value of an input element by its ID
function setInputValue(elementId, value) {
    const inputElement = document.getElementById(elementId);
    if (inputElement) {
        inputElement.value = value;
    }
}

// Function to update the coordinates and display them in input text fields
function updateCoordinates(latlng, latElementId, lngElementId) {
    const latInput = document.getElementById(latElementId);
    const lngInput = document.getElementById(lngElementId);

    latInput.value = latlng ? latlng.lat.toFixed(6) : '';
    lngInput.value = latlng ? latlng.lng.toFixed(6) : '';
}

// Function to update the address and display it in input text fields
function updateAddress(address, addressElementId) {
    const addressInput = document.getElementById(addressElementId);

    addressInput.value = address || '';
}

// Function to handle map click and update markers, coordinates, and addresses
map.on('click', function (e) {
    if (!originMarker) {
        // Create the origin marker if it doesn't exist
        originCoordinates = e.latlng;
        originMarker = L.marker(originCoordinates, { draggable: true }).addTo(map);
        updateCoordinates(originCoordinates, 'origin-lat', 'origin-lng');
        originMarker.bindTooltip('Origin', { permanent: true, className: 'custom-tooltip' }).openTooltip();

        // Get the address for the origin
        geocoder.reverse(e.latlng, map.options.crs.scale(map.getZoom()), (results) => {
            originAddress = results[0] ? results[0].name : 'Address not found';
            updateAddress(originAddress, 'origin-address');
        });

        // Listen to the marker's dragend event to update the coordinates
        originMarker.on('dragend', function (e) {
            originCoordinates = e.target.getLatLng();
            updateCoordinates(originCoordinates, 'origin-lat', 'origin-lng');

            // Get the updated address for the origin
            geocoder.reverse(originCoordinates, map.options.crs.scale(map.getZoom()), (results) => {
                originAddress = results[0] ? results[0].name : 'Address not found';
                updateAddress(originAddress, 'origin-address');
            });
        });
    } else if (!destinationMarker) {
        // Create the destination marker if origin marker exists but destination doesn't
        destinationCoordinates = e.latlng;
        destinationMarker = L.marker(destinationCoordinates, { draggable: true }).addTo(map);
        updateCoordinates(destinationCoordinates, 'destination-lat', 'destination-lng');
        destinationMarker.bindTooltip('Destination', { permanent: true, className: 'custom-tooltip' }).openTooltip();

        // Get the address for the destination
        geocoder.reverse(e.latlng, map.options.crs.scale(map.getZoom()), (results) => {
            destinationAddress = results[0] ? results[0].name : 'Address not found';
            updateAddress(destinationAddress, 'destination-address');
        });

        // Listen to the marker's dragend event to update the coordinates
        destinationMarker.on('dragend', function (e) {
            destinationCoordinates = e.target.getLatLng();
            updateCoordinates(destinationCoordinates, 'destination-lat', 'destination-lng');

            // Get the updated address for the destination
            geocoder.reverse(destinationCoordinates, map.options.crs.scale(map.getZoom()), (results) => {
                destinationAddress = results[0] ? results[0].name : 'Address not found';
                updateAddress(destinationAddress, 'destination-address');
            });
        });
    } else {
        // Remove both markers if both markers exist
        map.removeLayer(originMarker);
        map.removeLayer(destinationMarker);
        originMarker = null;
        destinationMarker = null;

        // Reset the coordinates display for both origin and destination
        updateCoordinates(null, 'origin-lat', 'origin-lng');
        updateCoordinates(null, 'destination-lat', 'destination-lng');

        // Reset the address display
        updateAddress('', 'origin-address');
        updateAddress('', 'destination-address');
    }
});

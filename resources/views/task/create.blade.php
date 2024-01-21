@extends('layouts.admin')
@section('title')
    <title>Tambah Task</title>
    
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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Task /</span> Tambah</h4>
    <form action="{{ route('task.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6">
        <div class="card mb-4">
            <h5 class="card-header">Tambah Task</h5>
            <div class="card-body demo-vertical-spacing demo-only-element">
                <div class="mb-3">
                <label class="form-label" for="basic-default-name">Name Task</label>
                <input
                    type="text"
                    class="form-control"
                    id="task_name"
                    name="task_name"
                    placeholder="Enter Name Task"
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
                ></textarea>
                <p class="text-danger">{{ $errors->first('task_description') }}</p>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                    <select class="form-select" id="user_id" name="user_id" required>
                    <option selected>Choose Sales...</option>
                    @foreach ($users as $row)
                
                    <option value="{{ $row->id }} {{ $row->longitude }} {{ $row->latitude }}">{{ $row->name }}</option>
                    @endforeach
                    </select>
                    <p class="text-danger">{{ $errors->first('sales_id') }}</p>
                    <label class="input-group-text" for="sales">Sales</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                    <select class="form-select" id="customer_id" name="customer_id" required>
                    <option selected>Choose Customer...</option>
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
        required
        readonly
        id="destination-lng"
    />
    </div>
    <input type="hidden" name="assign_from" value="{{ Auth::user()->id }}">
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
                <button type="submit" class="btn btn-primary">Tambah</button>
                <button type="reset" class="btn btn-warning">Reset</button>
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
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder@1.13.0/dist/Control.Geocoder.js"></script>

    <script>
        const map = L.map('map').setView([-6.9912, 110.4216], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        const geocoder = L.Control.Geocoder.nominatim();

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


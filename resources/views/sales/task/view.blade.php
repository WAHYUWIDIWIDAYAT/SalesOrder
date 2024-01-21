@extends('layouts.admin')
@section('title')
    <title>Tambah Task</title>
    
@endsection

@section('content')
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
            height: 500px;
        }
        .red-icon {
    background-color: red;
    color: white; /* Opsional, sesuaikan dengan kebutuhan Anda */
}
    </style>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<div class="content-wrapper">

<div class="container-xxl flex-grow-1 container-p-y">
@if (session('success'))
    <div class="alert alert-primary">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<br>
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Task /</span> Detail</h4>
 
    <div class="row">
    <div class="col-md-12">
    <div id="map"></div>
<br><br>
    </div>
    <br><br>
    <br><br>
        <div class="col-md-12">
        <div class="card mb-4">
        <h5 class="card-header">Detail Task</h5>
            <div class="card-body demo-vertical-spacing demo-only-element">
                <table class="table" style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <th style="border: 1px solid #ccc; border-left: 0; border-right: 1px solid #ccc; padding: 8px;">Name Task</th>
                        <td style="border: 1px solid #ccc; border-left: 0; border-right: 0; padding: 8px; width: 80%;">{{ $task->task_name }}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #ccc; border-left: 0; border-right: 1px solid #ccc; padding: 8px;">Description Task</th>
                        <td style="border: 1px solid #ccc; border-left: 0; border-right: 0; padding: 8px; width: 80%;">{!! $task->task_description !!}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #ccc; border-left: 0; border-right: 1px solid #ccc; padding: 8px;">Sales</th>
                        <td style="border: 1px solid #ccc; border-left: 0; border-right: 0; padding: 8px; width: 80%;">{{ $task->user->name }}</td>
                    </tr>
                </table>
                <p class="text-danger">{{ $errors->first('task_name') }}</p>
            </div>
        </div>
        </div>
        </div>

        <div class="col-md-12">
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
    <label class="form-label" for="basic-default-name">Estimate</label>
    <input
        type="text"
        class="form-control"
        name="estimate"
        placeholder="Estimasi"
        required
        readonly
        id="estimate"
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
<hr>
<div class="mb-3">
@if($task->proff != null)
    <div class="form-group">
        <label class="form-label" for="image">Bukti</label>
        <!--link to image-->
        <div class="input-group">
            <a href="{{ asset('storage/public/proff/'.$task->proff) }}" target="_blank">Lihat Bukti</a>
        </div>
    </div>
@else
<form action="{{ route('sales.task.proff' , $task->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label class="form-label" for="image">Upload Image</label>
        <div class="input-group">
            <input type="file" name="proff" id="image" class="form-control">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </div>
        <p class="text-danger">{{ $errors->first('image') }}</p>
    </div>
</form>
@endif
</div>

@if($task->proff != null && $task->task_status == 0)
<form action="{{ route('sales.task.confirm', $task->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="col-md-12">
        <br>
        <div class="card-body demo-vertical-spacing demo-only-element">
        <center>
        <button type="submit" class="btn btn-primary">Konfirmasi</button>

        <button type="button" class="btn btn-danger" onclick="window.history.back()">Kembali</button>
        </center>
        </div>
    </div>
    </form>
    @endif
        </div>
        </div>
    </div>
    </div>
    
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>

<script>
    var map = L.map('map').setView([0, 0], 13); 
    var currentRoute; 
    var originMarker; 
    var destinationMarker; 

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    function updateRoute() {
        var userId = "{{ Auth::user()->id }}";

        $.get("/sales/location/" + userId, function (data) {
            var originLat = data.lat;
            var originLng = data.long;
            var destinationLat = {{$task->destination_latitude}};
            var destinationLng = {{$task->destination_longitude}};

            var origin = L.latLng(originLat, originLng);
            var destination = L.latLng(destinationLat, destinationLng);
            if (originMarker) {
                map.removeLayer(originMarker);
            }
            if (destinationMarker) {
                map.removeLayer(destinationMarker);
            }
            if (currentRoute) {
                map.removeControl(currentRoute);
            }

            var originIcon = L.divIcon({ className: 'origin-icon', html: 'Origin', iconSize: [20, 20], iconAnchor: [10, 10], popupAnchor: [0, -10] });
            var destinationIcon = L.divIcon({ className: 'destination-icon', html: 'Destination', iconSize: [20, 20], iconAnchor: [10, 10], popupAnchor: [0, -10] });

            originMarker = L.marker(origin, { icon: originIcon });
            destinationMarker = L.marker(destination, { icon: destinationIcon });
            originMarker.bindPopup('Origin');
            destinationMarker.bindPopup('Destination');
            originMarker.addTo(map);
            destinationMarker.addTo(map);
            currentRoute = L.Routing.control({
                waypoints: [
                    L.latLng(originLat, originLng),
                    L.latLng(destinationLat, destinationLng)
                ],
                show: false,
               
                waypointMode: 'snap', 
                draggableWaypoints: false, 
                lineOptions: {
                    styles: [{ color: 'blue', opacity: 1, weight: 5 }]
                }
            }).addTo(map);
            setTimeout(updateRoute, 5000);
        });
    }
    updateRoute();
</script>

<script>
    $(document).ready(function(){
        $('#image').change(function(){
            if($(this).val() != ''){
                var data = new FormData();
                data.append('_token', '{{ csrf_token() }}');
                $.each($('#image')[0].files, function(i, file){
                    data.append('image', file);
                });
                $.ajax({
                    url:"{{ route('sales.task.proff' , $task->id) }}",
                    method:"POST",
                    data:data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    success:function(data){
                        $('#uploaded_image').html(data.image);
                    }
                })
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $.ajax({
            url: '/sales/task/estimation/{{ $task->id }}',
            method: 'GET',
            success: function(data) {
                if (data.status === 'success') {
                    $('#estimate').val(data.duration);
                } else {
                    alert('Error: ' + data.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Ajax request failed. Status: ' + status + ', Error: ' + error);
            }
        });
    });
</script>




@endsection


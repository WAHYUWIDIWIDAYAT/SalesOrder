@extends('layouts.admin')

@section('title')
    <title>Dashboard</title>
@endsection

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
        <div class="d-flex align-items-end row">
            <div class="col-sm-7">
            <div class="card-body">
                <h5 class="card-title text-primary">Welcome {{ auth()->user()->name }} 🎉</h5>
                <p class="mb-4">
                    Hallo, {{ auth()->user()->name }}. Selamat datang kembali di aplikasi <strong>Sales Tracking</strong>. Anda login sebagai Sales.
                </p>
                <a href="{{ route('task') }}" class="btn btn-primary">Lihat Task</a>
            </div>
            </div>
            <div class="col-sm-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-4">
                <img
                src="{{ asset('admin/assets/img/illustrations/man-with-laptop-light.png')}}"
                height="140"
                alt="View Badge User"
                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                data-app-light-img="illustrations/man-with-laptop-light.png"
                />
            </div>
            </div>
        </div>
        </div>
    </div>

    <div class="col-lg-12 mb-4 order-0">
    <div class="card">
        <div style="max-width: 100%; width: 100%; height: 300px;">
            <div id="display-google-map" style="height: 100%; width: 100%; max-width: 100%;">
                <div id="map" style="height: 100%; width: 100%; max-width: 100%;"></div>
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
    </div>
    <div class="row">
    <div class="col-md-12 col-lg-12 order-2 mb-4">
        <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Task Statistics</h5>
            <div class="dropdown">
            <button
                class="btn p-0"
                type="button"
                id="transactionID"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
            >

            </button>
          
            </div>
        </div>
        <div class="card-body">
            <ul class="p-0 m-0">
            <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-primary"
                    ><i class="bx bx-credit-card"></i>
                </span>
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                    <small class="text-muted d-block mb-1">Total Task</small>
                    <h6 class="mb-0">Total Task</h6>
                </div>
                <div class="user-progress d-flex align-items-center gap-1">
                    <h6 class="mb-0">{{ $tasks }}</h6>
                    <span class="text-muted">Task</span>
                </div>
                </div>
            </li>
            <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                <img src="{{ asset('admin/assets/img/icons/unicons/wallet.png')}}" alt="User" class="rounded" />
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                    <small class="text-muted d-block mb-1">Task Pending</small>
                    <h6 class="mb-0">Task Pending</h6>
                </div>
                <div class="user-progress d-flex align-items-center gap-1">
                    <h6 class="mb-0">{{ $pending_tasks }}</h6>
                    <span class="text-muted">Task</span>
                </div>
                </div>
            </li>
            <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                <img src="{{ asset('admin/assets/img/icons/unicons/chart.png')}}" alt="User" class="rounded" />
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                    <small class="text-muted d-block mb-1">Task Done</small>
                    <h6 class="mb-0">Task Done</h6>
                </div>
                <div class="user-progress d-flex align-items-center gap-1">
                    <h6 class="mb-0">{{ $confirmed_tasks }}</h6>
                    <span class="text-muted">Task</span>
                </div>
                </div>
            </li>
            <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                <img src="{{ asset('admin/assets/img/icons/unicons/cc-success.png')}}" alt="User" class="rounded" />
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                    <small class="text-muted d-block mb-1">Task Cancel</small>
                    <h6 class="mb-0">Task Cancel</h6>
                </div>
                <div class="user-progress d-flex align-items-center gap-1">
                    <h6 class="mb-0">{{ $canceled_tasks }}</h6>
                    <span class="text-muted">Task</span>
                </div>
                </div>
            </li>
            
            </ul>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection

@section('js')
<script>
        var mymap = L.map('map').setView([0, 0], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mymap);

        mymap.locate({setView: true, maxZoom: 16});

        function onLocationFound(e) {
            var radius = e.accuracy / 2;

            L.marker(e.latlng).addTo(mymap)
                .bindPopup("Anda berada dalam radius ini").openPopup();

            L.circle(e.latlng, radius).addTo(mymap);
        }

        mymap.on('locationfound', onLocationFound);

        function onLocationError(e) {
            alert("Lokasi Anda tidak dapat ditemukan: " + e.message);
        }

        mymap.on('locationerror', onLocationError);
    </script>
@endsection

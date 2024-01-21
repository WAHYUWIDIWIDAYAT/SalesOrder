@extends('layouts.admin')

@section('title')
    
@endsection

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<style>
    .bar-chart {
        position: relative;
        width: 100%;
        margin-top: 15px;
        padding-bottom: 1px;
    }

    .bar-chart > .legend {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 40px;
        margin-bottom: -45px;
        border-top: 1px solid #000;
    }

    .bar-chart > .legend > .label {
        position: relative;
        display: inline-block;
        float: left;
        width: 25%;
        text-align: center;
    }

    .bar-chart > .legend > .label:before {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        content: '';
        width: 1px;
        height: 8px;
        background-color: #000;
        margin-top: -8px;
    }

    .bar-chart > .legend > .label.last:after {
        display: block;
        position: absolute;
        top: 0;
        right: 0;
        left: auto;
        content: '';
        width: 1px;
        height: 8px;
        background-color: #000;
        margin-top: -8px;
    }
    .bar-chart > .legend > .label h4 {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .bar-chart > .chart {
        position: relative;
        width: 100%;
    }

    .bar-chart > .chart > .item {
        position: relative;
        width: 100%;
        height: 40px;
        margin-bottom: 10px;
        color: #fff;
        text-transform: uppercase;
    }

    .bar-chart > .chart > .item > .bar {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #7B66FF;
        z-index: 5;
        
        border-radius: 20px;
    }

    .bar-chart > .chart > .item > .bar > .persen {
        display: block;
        position: absolute;
        top: 0;
        right: 0;
        height: 40px;
        line-height: 40px;
        padding-right: 12px;
        z-index: 15;
    }

    .bar-chart > .chart > .item > .bar > .progress {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background-color: #C683D7;
        z-index: 10;
    }

    .bar-chart > .chart > .item > .bar > .progress > .title {
        display: block;
        position: absolute;
        height: 40px;
        line-height: 40px;
        padding-left: 12px;
        letter-spacing: 2px;
        z-index: 15;
    }
    </style>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
        <div class="d-flex align-items-end row">
            <div class="col-sm-7">
            <div class="card-body">
                <h5 class="card-title text-primary">Welcome {{ auth()->user()->name }} 🎉</h5>
                <p class="mb-4">
                    Hallo, {{ auth()->user()->name }}. Selamat datang kembali di aplikasi <strong>Sales Tracking</strong>. Anda login sebagai Supervisor.
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

    <!-- Total Revenue -->
    
    </div>
    <div class="row">
    <!-- Order Statistics -->
    <div class="col-md-6 col-lg-6 order-2 mb-4">
        <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between pb-0">
            <div class="card-title mb-0">
            <h5 class="m-0 me-2">User Statistics</h5>
            
            </div>
            
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
      
            </div>
            <br><br>
            <ul class="p-0 m-0">
            <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-primary"
                    ><i class="bx bx-user"></i>
                </span>
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                    <h6 class="mb-0">Total User</h6>
                    
                </div>
                <div class="user-progress">
                    <small class="fw-semibold">{{ $all_users }}</small>
                </div>
                </div>
            </li>
            <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-success"><i class="bx bx-user"></i></span>
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                    <h6 class="mb-0">Supervisor</h6>
                   
                </div>
                <div class="user-progress">
                    <small class="fw-semibold">{{ $users }}</small>
                </div>
                </div>
            </li>
            <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-info"><i class="bx bx-user"></i></span>
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                    <h6 class="mb-0">Sales</h6>
                    
                </div>
                <div class="user-progress">
                    <small class="fw-semibold">{{ $sales }}</small>
                </div>
                </div>
            </li>
            
            </ul>
        </div>
        </div>
    </div>
    <!--/ Order Statistics -->

    <!-- Expense Overview -->
    
    <!--/ Expense Overview -->

    <!-- Transactions -->
    <div class="col-md-6 col-lg-6 order-2 mb-4">
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
    
    <!--/ Transactions -->
    </div>
    
    <div class="row">
        <div class="col-md-12 col-lg-12 order-2 mb-4">
            <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                 <h5 class="card-title m-0 me-2">Statistic Pembelian Customer</h5>
            </div>
            <div class="bar-chart" style="width: 90%; margin: 0 auto; margin-top: 20px; margin-bottom: 20px;">
        <div class="chart clearfix">
            @foreach($customers as $customer)
                <div class="item">
                    <div class="bar">
                        <span class="persen">{{ number_format($customer['customer_percentage'], 2) }}%</span>

                        <div class="progress" data-persen="{{ number_format($customer['customer_percentage'], 2) }}">
                            <span class="title">{{ $customer['customer_name'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>

        </div>
       

    
@endsection

@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
        var map = L.map('map').setView([-7.797068, 110.370529], 7);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var userMarkers = L.layerGroup().addTo(map);

        function updateUserMarkers(users) {
            userMarkers.clearLayers();

            users.forEach(function (user) {
                var userName = user.name;
                var userMarker = L.marker([user.latitude, user.longitude])
                    .bindPopup(userName)
                    .openPopup();

                    
                userMarkers.addLayer(userMarker);
            });
        }

        function fetchUserLocations() {
            fetch("{{ route('sales.location') }}")
                .then(response => response.json())
                .then(data => {
                    updateUserMarkers(data);
                })
                .catch(error => {
                    console.error('Error fetching user locations:', error);
                });
        }

        fetchUserLocations();

        setInterval(fetchUserLocations, 10000);
    </script>
    <script>
$(document).ready(function(){
    barChart();

    $(window).resize(function(){
        barChart();
    });

    function barChart(){
        $('.bar-chart').find('.progress').each(function(){
            var itemProgress = $(this),
            itemProgressWidth = $(this).parent().width() * ($(this).data('persen') / 100);
            itemProgress.css('width', itemProgressWidth);
        });
    }
});
</script>


@endsection

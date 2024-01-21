@extends('layouts.admin')

@section('title')
    <title>List Pembelian</title>
@endsection

@section('content')
<main class="main">
<br><br>
    <div class="container-fluid">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pembelian \</span> List</h4>
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @if (session('success'))
                                <div class="alert alert-primary">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                        
                        </div>
                        <div class="card-body">
                            <form action="#" method="get">
                                <div class="input-group mb-3 col-md-3 float-right">
                                    <input type="text" name="q" class="form-control rounded mr-2" placeholder="Cari..." value="{{ request()->q }}" style="margin-right: 20px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Cari</button>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                    <table class="table table-hover" style="border-left: 0px; border-right: 0px; padding: 10px; overflow-x: scroll;">
                                    <thead>
                                    <tr style="border-bottom: 2px solid #e3e3e3; padding: 10px;">
                                           
                                            <th>Nama Customer</th>
                                            <th>Tanggal</th>
                                          
                                
                                            <th>Sales Name</th>
                                            <th>Alamat</th>
                                            <th>Nomor Telepon</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                                       
                                        <tr style="border-bottom: 2px solid #e3e3e3; padding: 10px;">
                                        @forelse ($purchaseOrders as $order)
                                            <td>
                                                <strong>{{ $order->customer->name }}</strong><br>
                                                
                                            </td>
                                            <td>
                                                {{ $order->created_at->format('d-m-Y') }}
                                            </td>
                                           
                                            <td>{{ $order->user->name }}</td>
                                            <td>{{ $order->address }}</td>
                                            <td>{{ $order->phone }}</td>
                                            <td>{{ $order->email }}</td>
                                            <td>
                                            
                                                    <div class="btn-group">
                                                    <a href="#" class="btn btn-primary btn-sm">
                                                        Edit
                                                    </a>
                                                    &nbsp;
                                                    <a href="{{ route('detail_order', $order->id) }}" class="btn btn-primary btn-sm">
                                                        Show
                                                    </a>
                                                    </div>
                                                    
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="float-right" style="margin-left: 80%; margin-bottom: 30px;">
                        
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</main>
@endsection

@section('js')
    <script>
        //setting pagination
        $('.pagination').addClass('float-right');
    </script>

@endsection

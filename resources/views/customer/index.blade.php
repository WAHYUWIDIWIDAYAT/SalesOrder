@extends('layouts.admin')

@section('title')
    <title>Customer</title>
@endsection

@section('content')
<main class="main">
<br><br>
    <div class="container-fluid">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account /</span> Customer</h4>
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
                            
                                    <a href="{{ route('customer.create') }}" class="btn btn-primary btn-sm float-right">Tambah</a>
                          
                        </div>
                        <div class="card-body">
                            <form action="{{ route('customer') }}" method="get">
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
                                            <!--user data-->
                                            <th>Nama</th>
                                            <th>Detail</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($customers as $customer)
                                        <tr>
                                            <td>{{ $customer->name }}</td>
                                            <td style="width: 10%;">
                                                <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Data tidak ditemukan</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="float-right" style="margin-left: 80%;">
   
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
        $('.pagination').addClass('float-right');
    </script>

@endsection

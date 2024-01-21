@extends('layouts.admin')

@section('title')
    <title>List Voucher</title>
@endsection

@section('content')
<main class="main">
    <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Voucher /</span> Tambah</h4>
        <div class="animated fadeIn">
            <div class="row">
            <!--if is_admin 1 show this-->
            @if(auth()->user()->is_admin == 1)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Kategori Baru</h4>
                        </div>
                        <div class="card-body">
                        <form action="{{ route('voucher.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="name">Voucher Code</label>
                                <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" placeholder="Masukkan Kode Voucher">
                                <p class="text-danger">{{ $errors->first('code') }}</p>
                            </div>
                            
                            <div class="form-group">
                                <label for="name">Discount</label>
                                <input type="text" name="discount" id="discount" class="form-control" value="{{ old('discount') }}" placeholder="Rp. 0">
                                <p class="text-danger">{{ $errors->first('discount') }}</p>
                            </div>
                            
                            <div class="form-group">
                                <label for="name">Expired Date</label>
                                <input type="date" name="expired_date" id="expired_date" class="form-control" value="{{ old('expired_date') }}" placeholder="Masukkan Expired Date">
                                <p class="text-danger">{{ $errors->first('expired_date') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="name">Status</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Non Active</option>
                                </select>
                                <p class="text-danger">{{ $errors->first('is_active') }}</p>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-sm">Tambah</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                @endif
                @if(auth()->user()->is_admin == 0)
                <div class="col-md-12 mt-3 mt-md-0">
              
                @elseif(auth()->user()->is_admin == 1)
                <div class="col-md-8 mt-3 mt-md-0">
                @endif
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">List Voucher</h4>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="table-responsive">
                            <table class="table table-hover" style="border-left: 0px; border-right: 0px; padding: 10px; overflow-x: scroll;">
                                    <thead>
                                        <tr>
                                     
                                            <th>No</th>
                                            <th>Kode Voucher</th>
                                            <th>Expired Date</th>
                                            <th>Discount</th>
                                            <th>Status</th>
                                            @if(auth()->user()->is_admin == 1)
                                            <th>Aksi</th>
                                            @elseif(auth()->user()->is_admin == 0)
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($discount as $val)
                                        <tr>
                                        
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $val->code }}</strong></td>

                                            <td>{{ $val->expired_date }}</td>
                                            <td>{{ number_format($val->discount, 2, ',', '.') }}</td>
                                            <td>
                                                @if ($val->is_active == '1')
                                                    <span class="badge" style="background-color: #00ff00;">Active</span>
                                                @else
                                                    <span class="badge" style="background-color: #ff0000;">Non Active</span>
                                                @endif
                                            </td>
                                            @if(auth()->user()->is_admin == 1)
                                            <td>
                                                <form action="{{ route('voucher.delete', $val->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="{{ route('voucher.edit', $val->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                            </td>
                                            @elseif(auth()->user()->is_admin == 0)
                                            @endif
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data</td>
                                        </tr>
                                        @endforelse
                                </table>
                            </div>
                            <br>
                            <div class="d-flex justify-content-center">
                        
                            </div>
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
        $(document).ready(function(){
            $('#discount').on('input', function(){
            var discount = $(this).val();

            if(discount === '' || discount === 'Rp. '){
                $(this).val('Rp. 0');
            } else {
            
                var numericDiscount = parseFloat(discount.replace(/\D/g, '')); 
                var formattedDiscount = numericDiscount.toLocaleString('id-ID'); 

                $(this).val('Rp. ' + formattedDiscount);
            }
        });

});

    </script>

@endsection
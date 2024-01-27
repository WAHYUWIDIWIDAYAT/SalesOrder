@extends('layouts.admin')

@section('title')
    <title>List Product</title>
@endsection

@section('content')
<main class="main">
<br><br>
    <div class="container-fluid">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Product \</span> List</h4>
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
                            
                            <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm float-right">Tambah</a>

                           
                        </div>
                        <div class="card-body">
                            <form action="{{ route('product') }}" method="get">
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
                                           
                                            <th>Gambar</th>
                                            <th>Nama Produk</th>
                                            <th>Stok</th>
                                            <th>Harga</th>
                                            <th>Deksripsi</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                                       
                                        <tr style="border-bottom: 2px solid #e3e3e3; padding: 10px;">
                                        @forelse ($products as $product)
                                            <td>
                                                <img src="{{ asset('storage/public/images/' . $product->image) }}" width="150px" height="150px" alt="{{ $product->name }}">
                                            </td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                
                                            </td>
                                            <td>
                                                {{ $product->stock }}
                                            </td>
                                           
                                            <td>
                                                Rp. {{ number_format($product->price) }}
                                            </td>
                                            </td>
                                            <td>
                                                <!-- {{ $product->description }} with !! -->
                                                {!! $product->description !!}
                                            </td>
                                          
                                            <td>
                                                  
                                                    <div class="btn-group">
                                                    <form action="">
                                                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                                        Edit
                                                    </a>
                                                    </form>
                                                    &nbsp;
                                                    <form action="">
                                                    <a href="{{ route('product.show', $product->id) }}" class="btn btn-primary btn-sm">
                                                        Show
                                                    </a>
                                                    </form>
                                                    &nbsp;
                                                    @if($product->delete == true)
                                                    <form action="{{ route('product.delete', $product->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="{{ route('product.delete', $product->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Delete</a>
                                                    </form>
                                                    @endif
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

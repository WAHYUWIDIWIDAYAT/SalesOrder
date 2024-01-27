@extends('layouts.admin')
@section('title')
    <title>Edit Task</title>
    
@endsection

@section('content')
<div class="content-wrapper">

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Product /</span> Detail</h4>
   
    <div class="row">
        <div class="col-md-6">
        <div class="card mb-4">
            <h5 class="card-header">Detail Produk</h5>
            <div class="card-body demo-vertical-spacing demo-only-element">
                <div class="mb-3">
                <label class="form-label" for="basic-default-name">Nama Produk</label>
                <input
                    type="text"
                    class="form-control"
                    id="name"
                    name="name"
                    placeholder="Enter Nama Product"
                    value="{{ $product->name }}"
                    disabled
                />
                <p class="text-danger">{{ $errors->first('name') }}</p>
                </div>
                <br>
                <div class="mb-3">
                <div class="card">
                    <div class="card-body">
                    {!! $product->description !!}
                    </div>
                </div>
                <p class="text-danger">{{ $errors->first('description') }}</p>
                </div>
            </div>
        </div>
        </div>

        <div class="col-md-6">
        <div class="card mb-4">
            <h5 class="card-header">Detail</h5>
            <div class="card-body demo-vertical-spacing demo-only-element">
            <div class="mb-3">
            <br>
            <div class="mb-3">
        <div class="mb-3">
            <label class="form-label" for="basic-default-name">Stock</label>
            <input
                type="number"
                class="form-control"
                id="stock"
                name="stock"
                value="{{ $product->stock }}"
                placeholder="Enter Stock"
                required
                disabled
            />
        </div>

        <div class="mb-3">
            <label class="form-label" for="basic-default-name">Harga</label>
            <input
                type="number"
                class="form-control"
                id="price"
                value="{{ $product->price }}"
                name="price"
                placeholder="Enter Price"
                disabled
            />
        </div>

        <div class="mb-3">
            <label class="form-label" for="basic-default-name">Image</label>
            <br>
            <center>
                <img id="preview-image" src="{{ asset('storage/public/images/'.$product->image) }}" alt="preview image" class="img-fluid" width="300" height="300">
            </center>
        </div>

        <input type="hidden" name="assign_from" value="{{ Auth::user()->id }}">
        </div>
        </div>
    </div>
    </div>
    <div class="row">
        <div class="col-md-6">
        </div>
    </div>
    <br><br>
 
</div>
<div class="content-wrapper">
</div>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder@1.13.0/dist/Control.Geocoder.js"></script>
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
@endsection


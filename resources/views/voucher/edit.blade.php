@extends('layouts.admin')

@section('title')
    <title>Edit Voucher</title>
@endsection

@section('content')
<main class="main">
    <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Voucher /</span> Edit</h4>
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Voucher</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('voucher.update', $voucher->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                 
                                <div class="form-group">
                                    <label for="name">Kode Voucher</label>
                                    <input type="text" name="code" id="code" class="form-control" value="{{ $voucher->code }}" >
                                    <p class="text-danger">{{ $errors->first('code') }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="name">Discount</label>
                               
                                    <input type="text" name="discount" id="discount" class="form-control" value="Rp. {{ number_format($voucher->discount) }}" >
                                    <p class="text-danger">{{ $errors->first('discount') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="name">Expired Date</label>
                                    <input type="date" name="expired_date" id="expired_date" class="form-control" value="{{ $voucher->expired_date }}" >
                                    <p class="text-danger">{{ $errors->first('expired_date') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="name">Status</label>
                                    <select name="is_active" id="is_active" class="form-control">
                                        <option value="1" {{ $voucher->is_active == 1 ? 'selected':'' }}>Active</option>
                                        <option value="0" {{ $voucher->is_active == 0 ? 'selected':'' }}>Non Active</option>
                                    </select>
                                    <p class="text-danger">{{ $errors->first('is_active') }}</p>
                                </div>



                                <div class="form-group">
                                    <button class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </form>
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
                // Convert the string to a number and format it
                var numericDiscount = parseFloat(discount.replace(/\D/g, '')); // Remove non-numeric characters
                var formattedDiscount = numericDiscount.toLocaleString('id-ID'); // Format as per Indonesian locale

                $(this).val('Rp. ' + formattedDiscount);
            }
        });

});

    </script>

@endsection
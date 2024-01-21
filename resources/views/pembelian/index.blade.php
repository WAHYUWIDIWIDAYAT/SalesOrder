@extends('layouts.admin')

@section('title')
    <title>List Product</title>
@endsection

@section('content')
<main class="main">
<br><br>
    <div class="container-fluid">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pembelian \</span> Produk</h4>
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
                        <div class="row">
                        <!-- ===================== nambah ini aku ===================== -->
                        <div class="col-md-6">
                <form action="{{ route('checkout') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card mb-4">
                            <h5 class="card-header">Data Pembelian</h5>
                            <div class="card-body demo-vertical-spacing demo-only-element">
                                <div class="mb-3">
                                <label class="form-label" for="basic-default-name">Name Customer</label>
                            
                                <select name="customer_id" id="customer_id" class="form-control">
                                    <option value="">Pilih Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>

                                <p class="text-danger">{{ $errors->first('name') }}</p>
                                </div>
                                <div class="mb-3">
                                <label class="form-label" for="basic-default-description">Catatan Pembelian</label>
                                <textarea
                                    class="form-control"
                                    id="notes"
                                    name="notes"
                                    placeholder="Enter Note"
                                    required
                                ></textarea>
                                <p class="text-danger">{{ $errors->first('notes') }}</p>
                                </div>
                            </div>
                            
                        </div>
                           <input type="text" name="q" id="searchInput" class="form-control" placeholder="Cari..." value="{{ request()->q }}" style="margin-right: 20px;">
                        <br>
                        <div class="card mb-4">

                        <div class="table-responsive">
                                    <table class="table table-hover" style="border-left: 0px; border-right: 0px; padding: 10px; overflow-x: scroll;">
                                    <thead>
                                    <tr style="border-bottom: 2px solid #e3e3e3; padding: 10px;">
                                           
                                            <th>Nama Produk</th>
                                            <th>Harga</th>
                                            <th>Quantity</th>
                                            <th>Select</th>
                                        </tr>
                                    </thead>
                            
                                    <tbody id="product-list">
                                    </tbody>
                                </table>
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
                        <label class="form-label" for="basic-default-name">Alamat</label>
                        <input
                            type="text"
                            class="form-control"
                            id="address"
                            name="address"
                            placeholder="Enter Alamat"
                            required
                        />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="basic-default-name">Nomor Telpon</label>
                        <input
                            type="text"
                            class="form-control"
                            id="phone"
                            name="phone"
                            placeholder="Enter Nomor Telpon"
                            required
                        />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="basic-default-name">Email</label>
                        <input
                            type="text"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="Enter Email"
                            required
                        />
                    </div>

                    <div class="mb-3">
                    <div class="col-md-12">
                        <label class="form-label" for="basic-default-name">Voucher</label>
                        <div class="input-group">
                            <input
                                type="text"
                                class="form-control"
                                id="voucher"
                                name="voucher"
                                placeholder="Enter Voucher"
                                required
                            />
                            <button type="button" class="btn btn-primary ms-2" id="applyBtn">Apply</button>
                        </div>
                    </div>
                    <p class="text-danger">{{ $errors->first('voucher') }}</p>
                </div>

                <div class="col-md-12">
                <div class="mb-3">
                    <div class="card">
                        <div class="card-body">
                            <p id="total">Total Product: 0</p>
                            <p id="subtotalDisplay">Subtotal: 0</p>
                            <p id="discountDisplay">Discount: 0</p>
                            <input type="hidden" name="discount" id="discount">
                            <input type="hidden" name="subtotal" id="subtotal">
                        </div>
                    </div>
                </div>
            </div>
                

                <input type="hidden" name="data_product" id="dataProduct">
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

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
                                <button type="submit" class="btn btn-primary">Beli Produk</button>
                                <button type="button" class="btn btn-danger" onclick="window.history.back()">Kembali</button>
                                </center>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
            </form>
                </div>

                <!-- ===================== sampe sini ===================== -->
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

    
   

<script>
    $(document).ready(function(){
        var idArray = [];

        function loadProducts() {
            $.ajax({
                url: 'select_product',
                type: "GET",
                dataType: "JSON",
                data: {
                    q: $('#searchInput').val() 
                },
                success: function(data) {
                    var product = '';
                    $.each(data.data, function(key, value){
                        product += '<tr>';
                        product += '<td>'+value.name+'</td>';
                        product += '<td>'+value.price+'</td>';
                        product += '<td>';
                        product += '   <input type="number" name="quantity[]" class="form-control quantity-input" placeholder="Enter Quantity" value="1" min="1" style="width: 70px;" required>';
                        product += '</td>';
                        product += '<td>';
                        product += '<input type="checkbox" name="product_id[]" value="'+value.id+'" class="product_id" id="product_id">';
                        product += '</td>';
                        product += '</tr>';
                    });

                    if (data.data.length === 0) {
                        product += '<tr>';
                        product += '<td colspan="4" class="text-center">Tidak ada data</td>';
                        product += '</tr>';
                    }

                    $('#product-list').html(product);

                    // Initialize checkboxes and quantities based on idArray
                    idArray.forEach(function(item) {
                        var checkbox = $('.product_id[value="'+item.id+'"]');
                        var quantityInput = checkbox.closest('tr').find('.quantity-input');
                        checkbox.prop('checked', true);
                        quantityInput.val(item.quantity);
                    });

                    // Calculate and display subtotal
                    calculateSubtotal();

                    console.log(idArray);
                }
            });
        }

        loadProducts();

        $('#searchInput').on('input', function() {
            loadProducts(); 
        });

        $('#searchButton').click(function() {
            loadProducts(); 
        });

        $(document).on('change', ':checkbox', function() {
            var id = $(this).val();
            var quantityInput = $(this).closest('tr').find('.quantity-input');

            if ($(this).is(':checked')) {
                var existingItem = idArray.find(item => item.id === id);
                if (!existingItem) {
                    idArray.push({ id: id, quantity: quantityInput.val() || 1, subtotal: 0 });
                } else {
                    // Update the quantity of the existing item
                    existingItem.quantity = quantityInput.val() || 1;
                }
            } else {
                var index = idArray.findIndex(item => item.id === id);
                if (index !== -1) {
                    idArray.splice(index, 1);
                }
            }

            // Calculate and display subtotal
            calculateSubtotal();

            // Recalculate and display total product count
            var total = idArray.length;
            $('#total').text('Total Product: ' + total);

            console.log(idArray);
            $('#dataProduct').val(JSON.stringify(idArray));
        });

        $(document).on('input', '.quantity-input', function() {
            var id = $(this).closest('tr').find('.product_id').val();
            var index = idArray.findIndex(item => item.id === id);

            if (index !== -1) {
                idArray[index].quantity = $(this).val() || '1';
            }

            // Calculate and display subtotal
            calculateSubtotal();

            $('#dataProduct').val(JSON.stringify(idArray));
            console.log(idArray);
        });

        function calculateSubtotal() {
            idArray.forEach(function(item) {
                // Assuming each item has a 'price' property
                // You may need to adjust this based on your actual data structure
                var priceText = $('.product_id[value="'+item.id+'"]').closest('tr').find('td:eq(1)').text();
                var price = parseFloat(priceText.replace(/[^0-9.-]+/g,"")); // Remove non-numeric characters

                if (!isNaN(price)) {
                    item.subtotal = price * item.quantity;
                }
            });

            // Sum up all subtotals
            var totalSubtotal = idArray.reduce((acc, item) => acc + item.subtotal, 0);

            // Format totalSubtotal as IDR with commas and two decimal places
            var formattedTotalSubtotal = totalSubtotal.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            console.log(formattedTotalSubtotal);

            // Update a display element with the formatted totalSubtotal
            $('#subtotal').val(totalSubtotal);
            $('#subtotalDisplay').text('Subtotal: ' + formattedTotalSubtotal);
        }
    });
</script>


<script>
    // Ensure this script runs after the DOM is fully loaded
    $(document).ready(function() {
        // if the apply button is clicked, then post the /checkVoucher route to get response data from the controller
        $('#applyBtn').click(function() {
     
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: 'checkVoucher',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    voucher: $('#voucher').val(),
                    _token: csrfToken 
                },
                success: function(data) {
                    
                    if (data.status === 'success') {
                        $('#discount').val(data.data.discount);
                        $('#discountDisplay').text('Discount: ' + data.data.discount);

                    } else {
                        $('#discountDisplay').text('Discount: ' + 0);
                    }
                }
            });
        });
    });
</script>

@endsection

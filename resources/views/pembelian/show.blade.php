@extends('layouts.admin')
@section('title')
    <title>Detail Pesanan</title>
@endsection

<style>
.gradient-custom {
/* fallback for old browsers */


/* Chrome 10-25, Safari 5.1-6 */

}
</style>

@section('content')
<section class="h-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-10 col-xl-12">
        <div class="card" style="border-radius: 10px;">
          <div class="card-header px-4 py-5">
            <h5 class="text-muted mb-0">Pesanan Pembelian, <span style="color: #65647C;">{{ $purchaseOrder->customer->name }}</span></h5>
          </div>
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <p class="lead fw-normal mb-0" style="color: #65647C;">Sales Name</p>
              <p class="small text-muted mb-0">Sales : <span class="fw-bold">{{ $purchaseOrder->user->name }}</span></p>
            </div>
            <div class="card shadow-0 border mb-4">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                
                    <th scope="col" class="text-center">Product Name</th>
                    <th scope="col" class="text-center">Description</th>
                    <th scope="col" class="text-center">Quantity</th>
                    <th scope="col" class="text-center">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseOrder->purchaseOrderDetail as $orderDetail)
                <tr>
                    <td class="text-center">{{ $orderDetail->product->name }}</td>
                    <td class="text-center small">{!! $orderDetail->product->description !!}</td>
                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                    <td class="text-center">Rp. {{ number_format($orderDetail->price, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

            <div>
            <div class="card shadow-0 border mb-4">
            <table class="table table-hover">
            <thead>
    
        <tr>
            <td class="fw-bold">Order Details</td>
        </tr>
        <tr>
            <td class="text-muted">Invoice Number</td>
            <td class="text-end">{{ $purchaseOrder->id }}</td>
        </tr>
        <tr>
            <td class="text-muted">Invoice Date</td>
            <td class="text-end">{{ $purchaseOrder->created_at->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td class="text-muted">Alamat</td>
            <td class="text-end">{{ $purchaseOrder->address }}</td>
        </tr>
        <tr>
            <td class="text-muted">Email</td>
            <td class="text-end">{{ $purchaseOrder->email }}</td>
        </tr>
        <tr>
            <td class="text-muted">Diskon Pembayaran</td>
            <td class="text-end">Rp. {{ number_format($purchaseOrder->discount, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-muted">Biaya Pengiriman</td>
            <td class="text-end">Gratis</td>
        </tr>
        <tr>
            <td class="text-muted">Subtotal</td>
            <td class="text-end">Rp. {{ number_format($purchaseOrder->subtotal, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Total</td>
            <td class="text-end fw-bold">Rp. {{ number_format($purchaseOrder->total, 2, ',', '.') }}</td>
        </tr>
    </thead>
</table>

</div>

        </div>
      </div>
    </div>
  </div>
  <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-dark text-uppercase" onclick="printInvoice()">Print</button>
    </div>

    <div class="modal fade" id="modalPrint" tabindex="-1" aria-labelledby="modalPrintLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 90%;">

            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPrintLabel">Print Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> 
            <div class="modal-body">
                <div class="invoice-container">
                    <center><div class="header">PT. Karya Abadi Jaya</div></center>
                    <br>
                    <div class="customer-details">
                        <p>Nama Customer: {{ $purchaseOrder->customer->name }}</p>
                        <p>Alamat: {{ $purchaseOrder->address }}</p>
                        <p>Nomor Telepon: {{ $purchaseOrder->phone }}</p>
                        <p>Email: {{ $purchaseOrder->email }}</p>
                    </div>
                </div>
                <div class="invoice-items-container">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchaseOrder->purchaseOrderDetail as $orderDetail)
                            <tr>
                                <td>{{ $orderDetail->product->name }}</td>
                                <td>{!! $orderDetail->product->description !!}</td>
                                <td>{{ $orderDetail->quantity }}</td>
                                <td>Rp. {{ number_format($orderDetail->price, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="invoice-footer" style="text-align: right;">
                    <div class="shipping">
                        Biaya Pengiriman: Gratis
                    </div>
                    <div class="discount">
                        Diskon: Rp. {{ number_format($purchaseOrder->discount, 2, ',', '.') }}
                    </div>
                    <div class="subtotal">
                        Subtotal: Rp. {{ number_format($purchaseOrder->subtotal, 2, ',', '.') }}
                    </div>
                    <div class="total">
                        Total: Rp. {{ number_format($purchaseOrder->total, 2, ',', '.') }}
                    </div>
                    <div class="footer">
                        Invoice Pembelian
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

</section>

<script>
    function printInvoice() {
        var printContents = document.getElementById("modalPrint").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

@endsection

@section('js')



</script>

@endsection
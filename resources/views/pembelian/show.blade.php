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
            <div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" class="text-center">Product Name</th>
                <th scope="col" class="text-center d-none d-md-table-cell">Description</th>
                <th scope="col" class="text-center d-none d-md-table-cell">Quantity</th>
                <th scope="col" class="text-center">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseOrder->purchaseOrderDetail as $orderDetail)
            <tr>
                <td class="text-center">{{ $orderDetail->product->name }}</td>
                <td class="text-center small d-none d-md-table-cell">{!! $orderDetail->product->description !!}</td>
                <td class="text-center d-none d-md-table-cell">{{ $orderDetail->quantity }}</td>
                <td class="text-center">Rp. {{ number_format($orderDetail->price, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>

</div>

            <div>
            <div class="card shadow-0 border mb-4">
            <div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <td class="fw-bold">Order Details</td>
            </tr>
            <tr>
                <td class="text-muted">Invoice Number</td>
                <td class="text-end">{{ $purchaseOrder->code }}</td>
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

            <tr>
                <td class="text-muted">Status</td>
                <td class="text-end"><span class="badge bg-{{ $purchaseOrder->paid == 1 ? 'success' : 'danger' }}">{{ $purchaseOrder->paid == 1 ? 'Lunas' : 'Belum Lunas' }}</span></td>
            </tr>
        </thead>
    </table>
</div>


</div>

        </div>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-end mt-3">
    @if ($purchaseOrder->status == 0)
        <form action="{{ route('accept_order', $purchaseOrder->id) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="accept">
            <button type="submit" class="btn btn-danger btn-md">Terima Pesanan</button>
        </form>
    @elseif ($purchaseOrder->status == 1)
      
        <form action="{{ route('accept_order', $purchaseOrder->id) }}" method="POST" class="d-inline-block">
            @csrf
            <input type="hidden" name="status" value="delivery">
            <button type="submit" class="btn btn-danger btn-md mt-0 mt-md-0">Kirim Pesanan</button>
            <a href="{{ route('invoice', $purchaseOrder->id) }}" class="btn btn-primary btn-md ms-2 mt-0 mt-md-0 d-inline-block align-top">Cetak Sales Order</a>
        </form>

    @elseif ($purchaseOrder->status == 2 && $purchaseOrder->paid == 0)
      
        <form action="{{ route('accept_order', $purchaseOrder->id) }}" method="POST" class="d-inline-block">
            @csrf
            <input type="hidden" name="status" value="paid">
            <button type="submit" class="btn btn-danger btn-md mt-0 mt-md-0">Pembayaran Lunas</button>
            <a href="{{ route('delivery_invoice', $purchaseOrder->id) }}" class="btn btn-primary btn-md">Cetak Delivery Order</a>
        </form>
    @elseif ($purchaseOrder->status == 2 && $purchaseOrder->paid == 1)
        <a href="{{ route('sales_invoice', $purchaseOrder->id) }}" class="btn btn-primary btn-md">Cetak Invoice</a>&nbsp;&nbsp;
        <a href="{{ route('delivery_invoice', $purchaseOrder->id) }}" class="btn btn-primary btn-md">Cetak Delivery Order</a>

    @endif
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
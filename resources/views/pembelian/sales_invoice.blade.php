<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .invoice-container {
            width: 80%;
            margin: 20px auto;
            border: 1px solid #ddd;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        .customer-details {
            margin-top: 20px;
        }

        .invoice-details {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <div class="invoice-container">
        <div class="header">PT Karya Abadi Jaya</div>
        <br>
        <div class="header" style="font-size: 20px;">Invoice Pembelian</div>

        <div class="customer-details">
            <p>Sales Order: {{ $purchaseOrder->code }}</p>
            <p>Delivery Order: {{ $purchaseOrder->delivery_code }}</p>
            <p>Nama Customer: {{ $purchaseOrder->customer->name }}</p>
            <p>Alamat: {{ $purchaseOrder->address }}</p>
            <p>Nomor Telepon: {{ $purchaseOrder->phone }}</p>
            <p>Email: {{ $purchaseOrder->email }}</p>
            <p>Tanggal Sales Order: {{ $purchaseOrder->created_at->format('d-m-Y') }}</p>
            <p>Status Pengiriman: {{ $purchaseOrder->status == 2 ? 'Sudah Dikirim' : 'Proses' }}</p>
            <p>Status Pembayaran: {{ $purchaseOrder->paid == 1 ? 'Lunas' : 'Belum Lunas' }}</p>
        </div>

        <div class="invoice-details">
            <table>
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Kuantitas</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrder->purchaseOrderDetail as $purchaseOrderDetail)
                        <tr>
                            <td>{{ $purchaseOrderDetail->product->name }}</td>
                            <td>{{ $purchaseOrderDetail->quantity }}</td>
                            <td>Rp. {{ number_format($purchaseOrderDetail->price) }}</td>
                            <td>Rp. {{ number_format($purchaseOrderDetail->quantity * $purchaseOrderDetail->price) }}</td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>

        <div class="total">
            Total: Rp. {{ number_format($purchaseOrder->total) }}
        </div>

        <div class="footer">
            <br><br>
            Terima kasih telah berbelanja di PT Karya Abadi Jaya
        </div>
    </div>

</body>

</html>

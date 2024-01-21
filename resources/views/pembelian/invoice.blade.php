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
        <div class="header">PT. Karya Anak Bangsa</div>

        <div class="customer-details">
            <p>Nama Customer: John Doe</p>
            <p>Alamat: Jl. ABC No. 123</p>
            <p>Nomor Telepon: 123-456-789</p>
            <p>Email: john@example.com</p>
            <p>Invoice Date: 01-01-2022</p>
        </div>

        <div class="invoice-details">
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Product A</td>
                        <td>2</td>
                        <td>Rp. 50,000</td>
                        <td>Rp. 100,000</td>
                    </tr>
                    <tr>
                        <td>Product B</td>
                        <td>1</td>
                        <td>Rp. 75,000</td>
                        <td>Rp. 75,000</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="total">
            Total: Rp. 175,000
        </div>

        <div class="footer">
            Invoice Pembelian
        </div>
    </div>

</body>

</html>

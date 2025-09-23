<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>Invoice #{{ $order->id }}</title>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                color: #333;
                margin: 0;
                padding: 0;
            }

            .container {
                padding: 30px;
            }

            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 40px;
            }

            .logo {
                font-size: 24px;
                font-weight: bold;
                color: #1a73e8;
            }

            h2 {
                color: #1a73e8;
                margin-bottom: 20px;
            }

            .details,
            .summary {
                margin-bottom: 20px;
            }

            .details p {
                margin: 5px 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: left;
            }

            th {
                background-color: #f0f4f8;
            }

            .total {
                font-weight: bold;
                font-size: 16px;
            }

            .status {
                padding: 5px 10px;
                color: white;
                border-radius: 4px;
                display: inline-block;
            }

            .pending {
                background-color: #f59e0b;
            }

            .accepted {
                background-color: #10b981;
            }

            .rejected {
                background-color: #ef4444;
            }

            .completed {
                background-color: #3b82f6;
            }

        </style>
    </head>

    <body>
        <div class="container">
            <div class="header">
                <div class="logo">MyServiceApp</div>
                <div>Invoice #{{ $order->id }}</div>
            </div>

            <h2>Detail Pesanan</h2>
            <div class="details">
                <p><strong>Nama Layanan:</strong> {{ $order->service->title }}</p>
                <p><strong>Alamat:</strong> {{ $order->customer_address }}</p>
                <p><strong>Nomor Telepon:</strong> {{ $order->customer_phone }}</p>
                <p><strong>Catatan:</strong> {{ $order->note ?? '-' }}</p>
                <p><strong>Metode Pembayaran:</strong> {{ ucfirst(str_replace('_',' ', $order->payment_method)) }}</p>
                <p><strong>Status:</strong>
                    <span class="status {{ strtolower($order->status) }}">
                        {{ ucfirst($order->status) }} (simulasi)
                    </span>
                </p>
            </div>

            <h2>Rincian Harga</h2>
            <table>
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Harga Layanan</td>
                        <td>Rp {{ number_format($order->price,0,',','.') }}</td>
                    </tr>
                    <tr>
                        <td>Fee Platform (5%)</td>
                        <td>Rp {{ number_format($order->platform_fee,0,',','.') }}</td>
                    </tr>
                    <tr class="total">
                        <td>Total Bayar</td>
                        <td>Rp {{ number_format($order->total_price,0,',','.') }}</td>
                    </tr>
                </tbody>
            </table>

            <p>Terima kasih telah menggunakan MyServiceApp. Ini adalah invoice simulasi untuk lomba.</p>
        </div>
    </body>

</html>

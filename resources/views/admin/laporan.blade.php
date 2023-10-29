<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .invoice {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-header h1 {
            font-size: 24px;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .invoice-details p {
            margin: 0;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .invoice-table th {
            background-color: #f2f2f2;
        }
        .invoice-total {
            text-align: right;
        }
        .invoice-footer {
            margin-top: 20px;
            text-align: center;
        }
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .custom-table th,
        .custom-table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
        }

        .custom-table th {
            color: #000;
            border-bottom: 2px solid #dee2e6;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="invoice-header">
            <h1>Laporan Penjualan</h1>
        </div>
        <div class="invoice-details">
            <table class="custom-table">
                <thead>
                    <tr>
                    <th>No.</th>
                    <th>Nama Pelanggan</th>
                    <th>Tanggal Transaksi Selesai</th>
                    <th>Total Belanja</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($penjualan as $key => $item)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $item->nama_pelanggan }}</td>
                    <td>{{ $item->tgl_selesai }}</td>
                    <td>Rp. {{ number_format($item->total_belanja) }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="total_omzet">
            <p><strong>Omzet:</strong> Rp. {{ number_format($detail) }}</p>
        </div>
    </div>
</body>
</html>

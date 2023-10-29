<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembelian</title>
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
            <h1>Bukti Pembelian</h1>
        </div>
        <div class="invoice-details">
            <table class="custom-table">
                <tr>
                    <th>
                        ID Trsansaksi
                    </th>
                    <td>
                        {{ $detail->id_transaksi }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Tanggal Pemesanan
                    </th>
                    <td>
                        {{ $detail->tgl_pesan }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Tanggal Selesai
                    </th>
                    <td>
                        {{ $detail->tgl_selesai }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Nama Pelanggan
                    </th>
                    <td>
                        {{ $detail->nama_pelanggan }}
                    </td>
                </tr>
                <tr>
                    <th>
                        No. Hp
                    </th>
                    <td>
                        {{ $detail->no_hp }}
                    </td>
                </tr>
            </table>
        </div>
        <table class="custom-table">
            <tr>
                <th>
                    Distribusi Barang
                </th>
                @if ($detail->kirim_ekspedisi === 1)
                <td>
                    Dikirim dengan ekspedisi
                </td>
                @elseif ($detail->kirim_ekspedisi === 0)
                <td>
                    Ambil langsung dari penjual
                </td>
                @endif
            </tr>
            @if ($detail->kirim_ekspedisi === 1)
            <tr>
                <th>
                    Biaya Ongkos Kirim
                </th>
                <td>
                    {{ number_format($detail->biaya_ongkir) }}
                </td>
            </tr>
            <tr>
                <th>
                    Alamat Pengiriman
                </th>
                <td>
                    {{ $detail->alamat }}, {{ $detail->kecamatan_tujuan }}, {{ $detail->kota_tujuan }}, {{ $detail->provinsi_tujuan }}
                </td>
            </tr>
            <tr>
                <th>
                    Kode Pos
                </th>
                <td>
                    {{ $detail->kode_pos }}
                </td>
            </tr>
            @endif
        </table>
        <h4>Daftar Pesanan</h4>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Harga (per item)</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    <td>{{ $item->nama_produk }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>Rp. {{ number_format($item->harga_produk) }}</td>
                    <td>Rp. {{ number_format($item->jumlah * $item->harga_produk) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="invoice-total">
            <p><strong>Total Belanja:</strong> Rp. {{ number_format($detail->total_belanja) }}</p>
        </div>
        <div class="invoice-footer">
            <p>Terima Kasih Sudah Berbelanja di UMKM Puti Mungka</p>
        </div>
    </div>
</body>
</html>

@extends('layouts/navadmin')
@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- upp -->
                        <h3 class="p-2"><strong>Detail Pesanan</strong></h3>
                        <table class="table p-2">
                            <tbody>
                                <tr>
                                    <th>Dipesan oleh</th>
                                    <td></td>
                                    <td>
                                    @if ($detail->role === 'admin')
                                    Admin
                                    @else
                                    Pelanggan
                                    @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nama Pelanggan</th>
                                    <td></td>
                                    <td>{{ $detail->nama_pelanggan }}</td>
                                </tr>
                                <tr>
                                    <th>No. HP</th>
                                    <td></td>
                                    <td>{{ $detail->no_hp }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pemesan</th>
                                    <td></td>
                                    <td>{{ $detail->tgl_pesan }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Selesai</th>
                                    <td></td>
                                    <td>{{ $detail->tgl_selesai}}</td>
                                </tr>
                                <tr>
                                    <th>Distribusi Barang</th>
                                    <td></td>
                                    <td>
                                        @if($detail->kirim_ekspedisi == 1)
                                            @php
                                                $dikirim = 'Dikirim dengan ekspedisi';
                                            @endphp
                                        @elseif($detail->kirim_ekspedisi == 0)
                                            @php
                                                $dikirim = 'Ambil langsung ke penjual';
                                            @endphp
                                        @else
                                            @php
                                                $dikirim = 'Dikirim dengan ekspedisi';
                                            @endphp
                                        @endif
                                        {{ $dikirim }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Belanja</th>
                                    <td></td>
                                    <td>Rp. {{ number_format($detail->total_belanja) }}</td>
                                </tr>
                                <tr>
                                    <th>Status Transaksi</th>
                                    <td></td>
                                    <td>
                                    <div style="display: flex;">
                                    @if ($detail->status_transaksi === 'Dibatalkan')
                                        <span style="color: red;">Pesanan telah dibatalkan</span>
                                    @elseif ($detail->status_transaksi === 'Selesai')
                                    <span style="color: blue;">Pesanan sudah diselesaikan</span>
                                    @else
                                    <select name="status_transaksi" id="status_transaksi" class="form control mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="Belum Dibayar" {{ $detail->status_transaksi === 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                        <option value="Menunggu Konfirmasi" {{ $detail->status_transaksi === 'Menunggu Konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                        <option value="Diproses" {{ $detail->status_transaksi === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="Sedang Dikirim" {{ $detail->status_transaksi === 'Sedang Dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                                        <option value="Sudah Diterima" {{ $detail->status_transaksi === 'Sudah Diterima' ? 'selected' : '' }}>Sudah Diterima</option>
                                    </select>
                                    <button class="btn btn-warning btn-sm ml-2" name="updateStatus" id="updateStatus">Update</button>
                                    </div>
                                    @endif
                                    </td>
                                </tr>
                                @if ($detail->bukti_bayar === null)
                                    <tr>
                                        <th>Bukti Bayar</th>
                                        <td></td>
                                        <td> Belum ada </td>
                                    </tr>
                                @else
                                    <tr>
                                        <th>Bukti Bayar</th>
                                        <td></td>
                                        <td>
                                            <img src="{{ url('buktibayar/' . $detail->bukti_bayar) }}" alt="Preview" width="100" height="100" style="display: inline-block; margin-right: 10px;">
                                            <a href="{{ url('buktibayar/' . $detail->bukti_bayar) }}" download>{{ $detail->bukti_bayar }}</a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>        
                        </table>
                        @if($detail->kirim_ekspedisi == 1)
                        <h6 class="mt-5 ml-2"><strong><span style="color: blue;">Alamat Pengiriman</span></strong></h6>
                        <table class="table p-2">
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $detail->alamat }}</td>
                            </tr>
                            <tr>
                                <th>Kecamatan</th>
                                <td>{{ $detail->kecamatan_tujuan }}</td>
                            </tr>
                            <tr>
                                <th>Kota/Kabupaten</th>
                                <td>{{ $detail->kota_tujuan }}</td>
                            </tr>
                            <tr>
                                <th>Provinsi</th>
                                <td>{{ $detail->provinsi_tujuan }}</td>
                            </tr>
                            <tr>
                                <th>Kode Pos</th>
                                <td>{{ $detail->kode_pos }}</td>
                            </tr>
                        </table>
                        @endif
                        <h5 class="mt-5"><strong>Daftar Pesanan</strong></h5>                  
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Warna</th>
                                <th scope="col">Bahan</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Harga (per item)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $data)
                                <tr>
                                    <th>{{++$key}}</th>
                                    <td>{{$data->nama_produk}}</td>
                                    <td>{{$data->nama_warna}}</td>
                                    <td>{{$data->nama_bahan}}</td>
                                    <td>{{$data->jumlah}}</td>
                                    <td>{{ number_format($data->harga_produk)}}</td>
                                </tr>   
                                @endforeach
                            </tbody>
                        </table>
                        <label for="note" class="block text-sm font-medium text-gray-700"> Catatan Pesanan</label>
                        <textarea name="note" id="note" rows="8" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" disabled >{{ $detail->note_transaksi }}</textarea>
                        @if ($detail->status_transaksi === 'Belum Dibayar' || $detail->status_transaksi === 'Diproses')
                        <a href="/batalkanpesananadm/{{ $detail->id }}">
                            <button class="btn btn-danger btn-sm float-right mt-5 mb-3" id="batalpesanan" name="batalpesanan" onclick="return confirm('Apakah anda yakin ingin membatalkan pesanan ini?')">Batalkan Pesanan</button>
                        </a>
                        @elseif ($detail->status_transaksi === 'Sedang Dikirim' || $detail->status_transaksi === 'Sudah Diterima')
                        <a href="/selesaikanpesananadm/{{ $detail->id }}">
                            <button class="btn btn-success btn-sm float-right mt-5 mb-3" id="selesaipesanan" name="selesaipesanan" onclick="return confirm('Apakah anda yakin ingin menyelesaikan pesanan ini?')">Selesaikan Pesanan</button>
                        </a>
                        @endif
                        <!-- downn -->
                    </div>
                </div>
            </div>
        </div>
<script>
    var id_transaksi = {{ $detail->id }};

    $('#updateStatus').on('click', function() {
            var status = document.getElementById("status_transaksi");
            var statusValue = status.value;
            console.log(id_transaksi, statusValue);
            $.ajax({
                url: "/updateStatus",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id_transaksi:id_transaksi,
                    status_transaksi:statusValue
                },
                cache: false,
                success: function(dataResult){
                    alert("Berhasil update status transaksi");
                    location.reload();
                },
                error:function(error){
                    alert("Gagal update status transaksi");
                }
            });
    });
</script>
@endsection
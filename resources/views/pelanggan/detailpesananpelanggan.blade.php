<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- uppp -->
                    <table class="table p-2">
                        <tbody>
                            <tr>
                                <th>Tanggal Pemesan</th>
                                <td>:</td>
                                <td>{{ $detail->tgl_pesan }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Selesai</th>
                                <td>:</td>
                                <td>{{ $detail->tgl_selesai}}</td>
                            </tr>
                            <tr>
                                <th>Distribusi Barang</th>
                                <td>:</td>
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
                                <td>:</td>
                                <td>Rp. {{ number_format($detail->total_belanja) }}</td>
                            </tr>
                            <tr>
                                <th>Status Transaksi</th>
                                <td>:</td>
                                <td>{{ $detail->status_transaksi }}</td>
                            </tr>
                                <tr>
                                    <th>Bukti Bayar<br>(format pdf, jpg, png, jpeg)</th>
                                    <td></td>
                                    <td>
                                        <form enctype="multipart/form-data" id="up_buktibayar" name="up_buktibayar">
                                        @csrf
                                        <input type="file" id="buktibayar" name="buktibayar">
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td></td>
                                    <td>
                                        <button class="btn btn-success btn-sm" id="uploadBtn" name="uploadBtn">Upload</button></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>
                                    @if ($detail->bukti_bayar !== null)
                                    <img src="{{ url('buktibayar/' . $detail->bukti_bayar) }}" alt="Preview" width="100" height="100" style="display: inline-block; margin-right: 10px;">
                                    <a href="{{ url('buktibayar/' . $detail->bukti_bayar) }}" download>{{ $detail->bukti_bayar }}</a>
                                    @endif
                                    </td>
                                </tr>
                        </tbody>        
                    </table>
                    <p class="mt-3"><strong>Daftar Pesanan</strong></p>                  
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
                      @if ($detail->status_transaksi === 'Belum Dibayar')
                      <button class="btn btn-danger btn-sm float-right mt-5 mb-3" id="batalpesanan" name="batalpesanan">Batalkan Pesanan</button>
                      @elseif ($detail->status_transaksi === 'Sedang Dikirim')
                        <a href="/selesaikanpesananpel/{{ $detail->id }}">
                            <button class="btn btn-success btn-sm float-right mt-5 mb-3" id="selesaipesanan" name="selesaipesanan" onclick="return confirm('Apakah anda yakin pesanan anda telah sesuai?')">Pesanan Diterima</button>
                        </a>
                      @endif
                      <!-- downnn -->
                </div>
            </div>
        </div>
    </div>

<script>
    let uploadBtn = document.getElementById('uploadBtn');
    let id_transaksi = {{ $detail->id }} ;
    uploadBtn.addEventListener('click', function() {
        var form = document.getElementById("up_buktibayar");
        var formData = new FormData(form);
        formData.append('id_transaksi', id_transaksi);
        for (const entry of formData.entries()) {
            console.log(entry[0] + ': ' + entry[1]);
        }
        console.log([...formData.entries()]);
        $.ajax({
                url: "/addBuktiBayar",
                type: "POST",
                processData: false,
                contentType: false,
                data:formData,
                cache: false,
                success: function(dataResult){
                    alert("Berhasil upload bukti bayar");
                    location.reload();
                },
                error: function (error) {
                    console.log(error.responseJSON.message);
                    alert("Gagal upload bukti bayar : " +error.responseJSON.message);
                }
            });
    });
</script>
</x-app-layout>
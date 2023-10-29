@extends('layouts/navadmin')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- upp -->
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <!-- dropdown filter by status -->
                        <form action="{{ route('pesananadmin') }}" method="GET">
                        <div class="form-group">
                            <label for="status">Filter Berdasarkan Status Transaksi:</label>
                            <select name="status" id="status" class="form-control">
                                <option value="all" @if ($status === 'all') selected @endif>Semua Transaksi</option>
                                <option value="Belum Dibayar" @if ($status === 'Belum Dibayar') selected @endif>Belum Dibayar</option>
                                <option value="Menunggu Konfirmasi" @if ($status === 'Menunggu Konfirmasi') selected @endif>Menunggu Konfirmasi</option>
                                <option value="Diproses" @if ($status === 'Diproses') selected @endif>Diproses</option>
                                <option value="Sedang Dikirim" @if ($status === 'Sedang Dikirim') selected @endif>Sedang Dikirim</option>
                                <option value="Sudah Diterima" @if ($status === 'Sudah Diterima') selected @endif>Sudah Diterima</option>
                                <option value="Selesai" @if ($status === 'Selesai') selected @endif>Selesai</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                    </div>
                </div>
                <!-- tab -->
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="table1-tab" data-toggle="tab" href="#table1" role="tab" aria-controls="table1" aria-selected="true">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="table2-tab" data-toggle="tab" href="#table2" role="tab" aria-controls="table2" aria-selected="false">Pelanggan</a>
                </li>
                </ul>
                <div class="tab-content" id="myTabsContent">
                <div class="tab-pane fade show active" id="table1" role="tabpanel" aria-labelledby="table1-tab">
                    <!-- Table 1 -->
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Nama Pelanggan</th>
                                        <th scope="col">Tanggal Pemesanan</th>
                                        <th scope="col">Tanggal Selesai</th>
                                        <th scope="col">Total Belanja</th>
                                        <th scope="col">Status Transaksi</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_admin as $key => $d)
                                        <tr>
                                            <th>{{ ++$key }}</th>
                                            <td>{{ $d->nama_pelanggan }}</td>
                                            <td>{{ $d->tgl_pesan }}</td>
                                            <td>
                                                @if ($d->tgl_selesai == null)
                                                    -
                                                @else
                                                    {{ $d->tgl_selesai }}
                                                @endif
                                            </td>
                                            <td>{{ number_format($d->total_belanja) }}</td>
                                            <td>{{ $d->status_transaksi }}</td>
                                            <td>
                                                <a href="/detailpesananadm/{{ $d->id_transaksi }}">
                                                    <button class="btn btn-success btn-sm"><i class="fa fa-info"
                                                            aria-hidden="true"></i>
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div>
                                {!! $data_admin->links() !!}
                            </div>
                        </div>
                    </div>
                    <!-- table 1 -->
                </div>
                <div class="tab-pane fade" id="table2" role="tabpanel" aria-labelledby="table2-tab">
                    <!-- Table 2-->
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Nama Pelanggan</th>
                                        <th scope="col">Tanggal Pemesanan</th>
                                        <th scope="col">Tanggal Selesai</th>
                                        <th scope="col">Total Belanja</th>
                                        <th scope="col">Status Transaksi</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_pelanggan as $key => $d)
                                        <tr>
                                            <th>{{ ++$key }}</th>
                                            <td>{{ $d->nama_pelanggan }}</td>
                                            <td>{{ $d->tgl_pesan }}</td>
                                            <td>
                                                @if ($d->tgl_selesai == null)
                                                    -
                                                @else
                                                    {{ $d->tgl_selesai }}
                                                @endif
                                            </td>
                                            <td>{{ number_format($d->total_belanja) }}</td>
                                            <td>{{ $d->status_transaksi }}</td>
                                            <td>
                                                <a href="/detailpesananbypel/{{ $d->id_transaksi }}">
                                                    <button class="btn btn-success btn-sm"><i class="fa fa-info"
                                                            aria-hidden="true"></i>
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div>
                                {!! $data_pelanggan->links() !!}
                            </div>
                        </div>
                    </div>
                    <!-- table 2 -->
                </div>
                </div>
                <!-- tab -->
                <!-- down -->
            </div>
        </div>
    </div>
</div>

<style>
    .pagination {
        float: left;
        margin-top: 10px;
        margin-left: 20px;
    }
</style>
@endsection

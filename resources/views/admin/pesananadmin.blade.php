@extends('layouts/navadmin')
@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- upp -->
                        <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Tanggal Pemesanan</th>
                            <th scope="col">Tanggal Selesai</th>
                            <th scope="col">Total Belanja</th>
                            <th scope="col">Status Transaksi</th>
                            <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $data)
                                <tr>
                                <th>{{ ++$key }}</th>
                                <td>{{ $data->tgl_pesan }}</td>
                                <td>
                                    @if ($data->tgl_selesai == null)
                                        -
                                    @else
                                        {{ $data->tgl_selesai }}
                                    @endif
                                </td>
                                <td>{{ number_format($data->total_belanja) }}</td>
                                <td>{{ $data->status_transaksi }}</td>
                                <td>
                                    <a href="/detailpesananadm/{{ $data->id }}">
                                    <button class="btn btn-success btn-sm"><i class="fa fa-info" aria-hidden="true"></i>
                                    </button>
                                    </a>
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                        <!-- down -->
                    </div>
                </div>
            </div>
        </div>
@endsection
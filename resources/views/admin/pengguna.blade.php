@extends('layouts/navadmin')
@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- uppp -->
                        <h4>Daftar Pelanggan</h4>
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Nama Pelanggan</th>
                                <th scope="col">Email</th>
                                <th scope="col">No. HP</th>
                                <th scope="col">Alamat</th>
                                <th style="text-align: center;width: 200px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $d)
                                    <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $d->nama_pelanggan }}</td>
                                    <td>{{ $d->email }}</td>
                                    <td>{{ $d->no_hp }}</td>
                                    <td>{{ $d->alamat }}</td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <a href="/detailprofilpelanggan/{{ $d->id_pelanggan }}"><button class="btn btn-success btn-sm"> <i class="fa fa-info p-1" aria-hidden="true"></i></button></a>
                                        <a href="/editprofilpeladmin/{{ $d->id_pelanggan }}"><button class="btn btn-warning btn-sm"> <i class="fa fa-pencil p-1" aria-hidden="true"></i></button></a>
                                        <a href="/deleteprofilpel/{{ $d->id_pelanggan }}"><button class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin ingin menghapus pengguna ini?');"><i class="fa fa-trash p-1" aria-hidden="true"></i></button></a>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            {!! $data->links() !!}
                        </div>
                        <!-- downnn -->
                    </div>
                </div>
            </div>
        </div>
<style>
    .pagination{
        float: left;
        margin-top: 10px;
        margin-left: 20px;
    }
</style>
@endsection
@extends('layouts/navadmin')
@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h5>Detail Profil Pelanggan</h5>
                        <div class="col-span-6 sm:col-span-4 p-3">
                                <label for="Nama Pelanggan" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                                <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ $data->nama_pelanggan }}" disabled>
                        </div>
                        <div class="col-span-6 sm:col-span-4 p-3">
                                <label for="No. HP" class="block text-sm font-medium text-gray-700">No. HP</label>
                                <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ $data->no_hp }}" disabled>
                        </div>
                        <div class="col-span-6 sm:col-span-4 p-3">
                                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="8" class="focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md shadow-sm" disabled>{{ $data->alamat }}</textarea>
                        </div>
                        <div class="col-span-6 sm:col-span-4 p-3">
                                <label for="Kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan</label>
                                <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ $nama_kecamatan }}" disabled>
                        </div>
                        <div class="col-span-6 sm:col-span-4 p-3">
                                <label for="kotakabupaten" class="block text-sm font-medium text-gray-700">Kota/Kabupaten</label>
                                <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ $nama_kota }}" disabled>
                        </div>
                        <div class="col-span-6 sm:col-span-4 p-3">
                                <label for="Provinsi" class="block text-sm font-medium text-gray-700">Provinsi</label>
                                <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ $nama_provinsi }}" disabled>
                        </div>
                        <div class="col-span-6 sm:col-span-4 p-3">
                                <label for="Kode Pos" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                                <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ $data->kode_pos }}" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
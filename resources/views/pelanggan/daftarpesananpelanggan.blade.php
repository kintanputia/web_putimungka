<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pesanan') }}
        </h2>
    </x-slot>

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
                            @foreach ($data as $key => $d)
                                <tr>
                                <th>{{ ++$key }}</th>
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
                                    <a href="/detailpesananpel/{{ $d->id }}">
                                    <button class="btn btn-success btn-sm"><i class="fa fa-info" aria-hidden="true"></i>
                                    </button>
                                    </a>
                                </td>
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
</x-app-layout>
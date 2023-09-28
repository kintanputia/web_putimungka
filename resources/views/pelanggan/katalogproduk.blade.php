<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Produk Kerajianan UMKM Puti Mungka') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- upp -->
                    <div class="container">
                        <div class="row">
                            @foreach($data as $data)
                            <div class="col-md-4 col-sm-6">
                                <div class="card mb-30"><a class="card-img-tiles" href="/katalogadmin/{{ $data->id }}" data-abc="true">
                                    <div class="inner">
                                    <?php $arrfoto=(array)json_decode($data->foto,true);  ?>
                                    @foreach ($arrfoto as $value)
                                        @if ($loop->first)
                                            <?php $pic1=$value  ?>
                                        @endif
                                    @endforeach
                                    <div class="main-img"><img src="{{ url('fotoproduk')}}/{{ $pic1 }}" alt="Category"></div>
                                    </div></a>
                                <div class="card-body text-center">
                                    <h4 class="card-title">{{ ucwords($data->nama_produk) }}</h4>
                                    <p class="text-muted">Rp. {{ number_format($data->harga) }}</p>
                                    <a class="btn btn-outline-primary btn-sm" href="/katalogproduk/{{ $data->id }}" data-abc="true">Lihat Produk</a>
                                </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- dow -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
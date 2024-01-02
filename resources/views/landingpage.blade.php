@extends('layouts/tanpalogin')
@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- about us -->
                        <div class="jumbotron text-center" style="background-color: #f3e9d2;">
                            <h2 class="display-4">UMKM Puti Mungka</h2>
                            <p class="lead">
                            UMKM Puti Mungka menyediakan berbagai macam produk kerajinan sulam dan bordir yang dibuat langsung oleh berbagai pengrajin yang tersebar di area Kota Payakumbuh dan Kabupaten 50 Kota
                            </p>
                        </div>
                        <!-- about us end -->
                        <hr class="m-0 pt-6 mt-6">
                        <!-- product terbaru -->
                        <div class="container">
                            <div class="border border-gray-300 p-6 rounded">
                            <h2 class="font-semibold text-gray-800 leading-tight text-center mb-5">Produk Terbaru Kami</h2>
                                <div class="row">
                                @foreach($produk as $d)
                                <div class="col-md-4 col-sm-6">
                                    <div class="card mb-30"><a class="card-img-tiles" href="/katalogproduktl/{{ $d->id_produk }}" data-abc="true">
                                        <div class="inner">
                                        <?php $arrfoto=(array)json_decode($d->foto,true);  ?>
                                        @foreach ($arrfoto as $value)
                                            @if ($loop->first)
                                                <?php $pic1=$value  ?>
                                            @endif
                                        @endforeach
                                        <div class="main-img">
                                            <img class="product-image" src="{{ url('fotoproduk')}}/{{ $pic1 }}" alt="Category"></div>
                                        </div></a>
                                    <div class="card-body text-center">
                                        <h4 class="card-title">{{ ucwords($d->nama_produk) }}</h4>
                                        <p class="text-muted">{{ $d->price_range }}</p>
                                        <a class="btn btn-outline-primary btn-sm" href="/katalogproduktl/{{ $d->id_produk }}" data-abc="true">Lihat Produk</a>
                                    </div>
                                    </div>
                                </div>
                                @endforeach
                                    <!-- button more -->
                                    <div class="mx-auto p-3">
                                        <a class="btn btn-success btn-lg float-right mr-3" href="/katalogproduktl" data-abc="true">Lebih Banyak Produk <i class="fa fa-arrow-right ml-2"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- product end -->
                    </div>
                </div>
            </div>
        </div>

<footer class="bg-brown text-white text-center py-3">
    <div class="container">
        @php
        $whatsappURL = "https://wa.me/6282298204102?text=Saya%20ingin%20bertanya%20tentang%20......";
        @endphp
        <a href="{{ $whatsappURL }}">
            <button class="btn btn-success btn-sm mb-3">
                <i class="fa fa-whatsapp p-1" aria-hidden="true"></i>Hubungi Kami
            </button> <br>
        </a>
        &copy; {{ date('Y') }} Website UMKM Puti Mungka
    </div>
</footer>
@endsection

<style>
    .bg-brown {
        background-color: #BBB19C;
    }
    .product-image {
        width: 150px;
        height: 200px;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        background-color: #f0f0f0;
    }

    .product-image img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
    }
</style>
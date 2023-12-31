@extends('layouts/navadmin')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div></div>
                    <div class="col-lg-12 mt-3">
                        <div class="row mb-5">
                            <!-- button tambah produk -->
                            <div class="col-lg-12 mt-3">
                                <a href="/tambahproduk">
                                <button class="btn btn-success btn-lg float-right">
                                <i class="fa fa-plus p-2"></i>Tambah Produk
                                </button></a>
                            </div>
                            <div class="col-lg-12 mt-3">
                                <a href="/kelolawarna">
                                <button class="btn btn-info btn-sm float-right">
                                <i class="fa fa-pencil p-2"></i>Kelola Warna
                                </button></a>
                                <a href="/kelolabahan">
                                <button class="btn btn-dark btn-sm float-right mr-2">
                                <i class="fa fa-pencil p-2"></i>Kelola Bahan
                                </button></a>
                            </div>
                        </div>
                <div class="container">
                <div class="row">
                    @foreach($produk as $data)
                    <div class="col-md-4 col-sm-6">
                        <div class="card mb-30"><a class="card-img-tiles" href="/katalogadmin/{{ $data->id_produk }}" data-abc="true">
                            <div class="inner">
                                <?php $arrfoto=(array)json_decode($data->foto,true);  ?>
                                @foreach ($arrfoto as $value)
                                    @if ($loop->first)
                                        <?php $pic1=$value  ?>
                                    @endif
                                @endforeach
                                <div class="main-img">
                                    <div class="card-img-container">
                                        <img class="card-img" src="{{ url('fotoproduk')}}/{{ $pic1 }}" alt="Category">
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="card-body text-center">
                            <h4 class="card-title">{{ ucwords($data->nama_produk) }}</h4>
                            <p class="text-muted">{{ $data->price_range }}</p>
                            <a class="btn btn-outline-primary btn-sm" href="/katalogadmin/{{ $data->id_produk }}" data-abc="true">Lihat Produk</a>
                        </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                </div>
                </div>
                <div>
                    {!! $produk->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .card-img-container {
        position: relative;
        width: 100%;
        padding-top: 75%;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    .card-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>

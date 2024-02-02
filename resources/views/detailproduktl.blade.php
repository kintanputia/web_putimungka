@extends('layouts/tanpalogin')
@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- up -->
                        <div class="container justify-content-center">
                                    <div class="col-lg-12">
                                        <div class="right-side-pro-detail border p-3 m-0">
                                            <div class="row">
                                            <?php $arrfoto=(array)json_decode($informasi->foto,true);?>
                                            <!-- caraousel start -->
                                                <div id="imageCarousel" class="carousel slide" data-ride="carousel">
                                                <!-- Indicators -->
                                                <ul class="carousel-indicators">
                                                    @foreach ($arrfoto as $key => $image)
                                                        <li data-target="#imageCarousel" data-slide-to="{{ $key }}" class="{{ $loop->first ? 'active' : '' }}"></li>
                                                    @endforeach
                                                </ul>

                                                <!-- Slides -->
                                                <div class="carousel-inner">
                                                    @foreach ($arrfoto as $key => $image)
                                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                        <div class="fixed-size-image">
                                                            <img src="{{ url('fotoproduk') }}/{{ $image }}" alt="Slide {{ $key + 1 }}" class="img-fluid">
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>

                                                <!-- Controls -->
                                                <a class="carousel-control-prev" href="#imageCarousel" role="button" data-slide="prev">
                                                    <div class="carousel-control-icon-bg">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    </div>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="carousel-control-next" href="#imageCarousel" role="button" data-slide="next">
                                                    <div class="carousel-control-icon-bg">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    </div>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                                </div>
                                            <!-- carousel end -->
                                                <div class="col-lg-12">
                                                    <h3><strong>{{ ucwords($informasi->nama_produk) }}</strong></h3>
                                                </div>
                                                <div class="col-lg-12">
                                                    <p class="m-0 p-0 price-pro">{{ $informasi->price_range }}</p>
                                                    <hr class="p-0 m-0">
                                                </div>
                                                <div class="col-lg-12 pt-2">
                                                    <h5>Detail Produk</h5>
                                                    <table class="table-borderless p-2">
                                                        <tbody>
                                                            <tr>
                                                                <th>Model</th>
                                                                <td>:</td>
                                                                <td>{{ $informasi->model }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Nama Motif</th>
                                                                <td>:</td>
                                                                <td>{{ $informasi->nama_motif }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Jenis Jahitan</th>
                                                                <td>:</td>
                                                                <td>{{ $informasi->jenis_jahitan }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Ukuran</th>
                                                                <td>:</td>
                                                                <td>{{ $informasi->ukuran }} cm</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Berat</th>
                                                                <td>:</td>
                                                                <td>{{ $informasi->berat }} gram</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Waktu Pengerjaan </th>
                                                                <td>:</td>
                                                                <td>{{ $informasi->waktu_pengerjaan }} hari</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Warna</th>
                                                                <td>:</td>
                                                                <?php
                                                                $dataArray = json_decode($warna, true);
                                                                $namaWarnaArray = [];
                                                                foreach ($dataArray as $item) {
                                                                    $namaWarna = $item['nama_warna'];
                                                                    $namaWarnaArray[] = $namaWarna;
                                                                }
                                                                $strw = implode(', ', $namaWarnaArray);
                                                                ?>
                                                                <td>{{ $strw }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Bahan</th>
                                                                <td>:</td>
                                                                <?php
                                                                $dataArray = json_decode($bahan, true);
                                                                $namaBahanArray = [];
                                                                foreach ($dataArray as $item) {
                                                                    $namaBahan = $item['nama_bahan'];
                                                                    $namaBahanArray[] = $namaBahan;
                                                                }
                                                                $strb = implode(', ', $namaBahanArray);
                                                                ?>
                                                                <td>{{ $strb }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <hr class="m-0 pt-2 mt-2">
                                                    <h5>Deskripsi Produk</h5>
                                                    <textarea name="deskripsi" id="deskripsi" rows="8" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" disabled>{{ $informasi->deskripsi }}</textarea>
                                                    <hr class="m-0 pt-2 mt-2">
                                                </div>
                                                <div class="col-lg-12 mt-3">
                                                    <div class="p-1">
                                                        @php
                                                            $encodedNamaProduk = urlencode($informasi->nama_produk);
                                                            $whatsappURL = "https://wa.me/6282298204102?text=Saya%20ingin%20bertanya%20tentang%20produk%20$encodedNamaProduk";
                                                        @endphp
                                                        <a href="{{ $whatsappURL }}">
                                                        <button class="btn btn-success btn-sm mr-3 float-right">
                                                        <i class="fa fa-whatsapp p-1" aria-hidden="true"></i>Tanya Produk ke Penjual
                                                        </button></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- down -->
                    </div>
                </div>
            </div>
        </div>
@endsection

<style>
    .fixed-size-image {
        width: 350px;
        height: 350px;
        margin: 0 auto; 
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    .fixed-size-image img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
    }
    .carousel-indicators li {
        background-color: #BCA987;
    }

    .carousel-indicators .active {
        background-color: #867555;
    }
    .carousel-control-prev,
    .carousel-control-next {
        font-size: 20px;
        padding: 10px;
    }

    .carousel-control-icon-bg {
        background-color: #C2A875;
        border-radius: 50%;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        color: #FF0000;
    }
</style>
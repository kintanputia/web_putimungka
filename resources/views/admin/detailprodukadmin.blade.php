@extends('layouts/navadmin')
@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- uppppppp -->
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
                                                    <p class="m-0 p-0 price-pro">Rp. {{ number_format($informasi->harga) }}</p>
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
                                                                <td>{{ $informasi->waktu_pengerjaan }}</td>
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
                                                        <button type="button" class="btn btn-info btn-sm float-left mr-2" id="btn_tmbh_keranjang" data-toggle="modal" data-target="#tambahkeranjang" >
                                                        <i class="fa fa-shopping-cart p-1" aria-hidden="true"></i>Tambah ke Keranjang
                                                        </button>
                                                        <a href="/deleteproduk/{{ $informasi->id_produk }}">
                                                        <button class="btn btn-danger btn-sm float-right" onclick="return confirm('Apakah anda yakin ingin menghapus produk ini?');">
                                                        <i class="fa fa-trash p-1" aria-hidden="true"></i>Hapus
                                                        </button></a>
                                                        <a href="/editproduk/{{ $informasi->id_produk }}">
                                                        <button class="btn btn-warning btn-sm float-right mr-2">
                                                        <i class="fa fa-pencil p-1" aria-hidden="true"></i>Edit
                                                        </button></a>
                                                    </div>
                                                    <!-- start modal -->
                                                    <div class="modal fade" id="tambahkeranjang" tabindex="-1" role="dialog" aria-labelledby="modalSayaLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h3 class="modal-title" id="modalSayaLabel">Detail Pesanan</h3>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                <form method="post" name="frm_addker" id="frm_addker" action="/tambahkeranjang" enctype="multipart/form-data" onsubmit="validasi()">
                                                                @csrf
                                                                    <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
                                                                    <input type="hidden" name="id_produk" id="id_produk" value="{{ $informasi->id_produk }}">
                                                                    <div class="form-group">
                                                                        <div class="text-center">
                                                                            <label for="quantity" style="display: inline-block;">Quantity:</label>
                                                                        </div>
                                                                        <div class="input-group col-lg-12 justify-center">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-outline-secondary" id="decrement">-</button>
                                                                            </div>
                                                                            <input type="text" class="form-control col-lg-2 text-center" id="jumlah" name="jumlah" value="1">
                                                                            <div class="input-group-append">
                                                                                <button type="button" class="btn btn-outline-secondary" id="increment">+</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <label for="warna" class="form-label">Warna</label>
                                                                    <select class="form-control mb-2" id="warna" name="warna" data-size="5">
                                                                        <option value="" selected>---- Pilih Warna ----</option>
                                                                        @foreach ($warna as $warna)
                                                                        <option value="{{ $warna->id_warna }}">{{ $warna->nama_warna }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label for="bahan" class="form-label">Bahan</label>
                                                                    <select class="form-control mb-2" id="bahan" name="bahan" data-size="5">
                                                                        <option value="" selected>---- Pilih Bahan ----</option>
                                                                        @foreach ($bahan as $bahan)
                                                                        <option value="{{ $bahan->id_bahan }}">{{ $bahan->nama_bahan }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="form-check mt-3 ml-2">
                                                                        <input class="form-check-input" type="checkbox" id="tambahmotif">
                                                                        <label for="tambahmotif" class="form-label">Ingin menambah motif? (Tambahan biaya Rp. 20.000)</label>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button id="btn_add_pesanan" type="button" class="btn btn-success" onclick="validasi()">Tambah</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                </div>
                                                                </div>
                                                            </div>
                                                            </div>
                                                    <!-- end modal -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <!-- downnnnn -->
                    </div>
                </div>
            </div>
        </div>
<!-- tampil error message gagal hapus produk -->
@if(session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
@endif
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var quantityInput = $('#jumlah');
        var incrementButton = $('#increment');
        var decrementButton = $('#decrement');

        incrementButton.click(function() {
            var currentValue = parseInt(quantityInput.val());
            if (!isNaN(currentValue)) {
                quantityInput.val(currentValue + 1);
            }
        });

        decrementButton.click(function() {
            var currentValue = parseInt(quantityInput.val());
            if (!isNaN(currentValue) && currentValue > 1) {
                quantityInput.val(currentValue - 1);
            }
        });
    });
</script>
<script>
    function validasi(){
        let a = document.forms["frm_addker"]["id_user"].value;
        let b = document.forms["frm_addker"]["id_produk"].value;
        let c = document.forms["frm_addker"]["jumlah"].value;
        let d = document.forms["frm_addker"]["warna"].value;
        let e = document.forms["frm_addker"]["bahan"].value;

        // cek kelengkapan data
        if (c <= 0){
            alert("Jumlah produk minimal 1");
            valid = false;
        }
        else if (d == ""){
            alert("Harap pilih warna produk");
            valid = false;
        }
        else if (e == ""){
            alert("Harap pilih bahan produk");
            valid = false;
        }
        else {
            console.log('data pesanan sudah lengkap');
            submitForm();
        }
    }
</script>
<script>
    var additionalCost = 0;
    var tambah_motif = 0;
    var final_price = {{ $informasi->harga }};

    var checkbox = document.getElementById("tambahmotif");
    checkbox.addEventListener("change", function() {
        if (checkbox.checked) {
            tambah_motif = 1;
            final_price = {{ $informasi->harga }} + 20000;
        } else {
            final_price = {{$informasi->harga}};
        }
    });

    function submitForm(){
            var form = $("#frm_addker");
            var forms = $('#frm_addker')[0];
            var url = form.attr('action');
            var formData = new FormData(forms);
            formData.append('harga_produk', final_price);
            formData.append('tambahan_motif', tambah_motif);
            $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            url: url,
            data:formData,
            success: function () {
                alert("Produk Berhasil Ditambahkan ke Keranjang");
                window.location.reload();
            },
            error: function (xhr, status, error) {
                var errorMessage = "Gagal";
                if (xhr.responseText) {
                    errorMessage = xhr.responseText;
                }
                console.log(errorMessage);
                alert("Produk Gagal Ditambahkan ke Keranjang");
            }
        });
        }
</script>
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
        background-color: #E9EFFF;
    }

    .carousel-indicators .active {
        background-color: #C2A875;
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
@endsection
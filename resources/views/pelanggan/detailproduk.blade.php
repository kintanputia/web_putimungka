<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Produk Kerajinan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- uppp -->
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
                                                        <button type="button" class="btn btn-info btn-sm float-right mr-2" id="btn_tmbh_keranjang" data-toggle="modal" data-target="#tambahkeranjang" >
                                                        <i class="fa fa-shopping-cart p-1" aria-hidden="true"></i>Tambah ke Keranjang
                                                        </button>
                                                        @php
                                                            $encodedNamaProduk = urlencode($informasi->nama_produk);
                                                            $whatsappURL = "https://wa.me/6282298204102?text=Saya%20ingin%20bertanya%20tentang%20produk%20$encodedNamaProduk";
                                                        @endphp
                                                        <a href="{{ $whatsappURL }}">
                                                        <button class="btn btn-success btn-sm mr-3 float-right">
                                                        <i class="fa fa-whatsapp p-1" aria-hidden="true"></i>Tanya Produk ke Penjual
                                                        </button></a>
                                                        <!-- <button type="button" id="btn_cek_ruangan" class="btn btn-info" data-toggle="modal" data-target="#cek" >Cek Pemakaian Ruangan</button> -->
                                                            <!-- Start Modal -->
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
                                                                @if ($pelanggan === null)
                                                                <h6>Harap lengkapi profil pelanggan terlebih dahulu</h6>
                                                                <p>  </p>
                                                                @else
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
                                                                        <option value="{{ $bahan->id_bahan }}" data-harga="{{ $bahan->harga }}">{{ $bahan->nama_bahan }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label for="harga_produk" class="form-label">Harga Produk</label>
                                                                    <input type="text" class="form-control" id="harga_produk" name="harga_produk">
                                                                    <div class="form-check mt-3 ml-2">
                                                                        <input class="form-check-input" type="checkbox" id="tambahmotif">
                                                                        <label for="tambahmotif" class="form-label">Ingin menambah motif? (Tambahan biaya Rp. 20.000)</label>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button id="btn_add_pesanan" type="button" class="btn btn-success" onclick="validasi()">Tambah</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                </div>
                                                                @endif
                                                                
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
                        </div>
                    <!-- downn -->
                </div>
            </div>
        </div>
    </div>
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
    var hargaProdukInput = document.getElementById('harga_produk');
    var bahanDropdown = document.getElementById('bahan');
    var checkbox = document.getElementById("tambahmotif");
    var tambah_motif = 0;
    var basePrice = 0;

    // set harga berdasarkan bahan terpilih
    bahanDropdown.addEventListener('change', function() {
        var selectedBahanOption = bahanDropdown.options[bahanDropdown.selectedIndex];
        var selectedBahanHarga = selectedBahanOption.getAttribute('data-harga');
        basePrice = parseFloat(selectedBahanHarga) || 0;
        hargaProdukInput.value = selectedBahanHarga || 0;
        checkbox.checked = false;
    });

    checkbox.addEventListener("change", function() {
        tambah_motif = checkbox.checked ? 1 : 0;

        if (tambah_motif) {
            final_price = basePrice + 20000;
        } else {
            final_price = basePrice;
        }

        hargaProdukInput.value = final_price;
    });

    function submitForm(){
            var harga_final = $('#harga_produk').val();
            var form = $("#frm_addker");
            var forms = $('#frm_addker')[0];
            var url = form.attr('action');
            var formData = new FormData(forms);
            formData.append('harga_produk', harga_final);
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
</x-app-layout>
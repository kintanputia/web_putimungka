@extends('layouts/navadmin')
@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- uppp -->
                        <div class="row d-flex justify-content-center my-4">
                    <div class="col-md-8">
                        <div class="card mb-4">
                        <div class="card-body">
                            <!-- product card upp -->
                            @foreach($uniqueV as $data)
                                <!-- Single item -->
                                <div class="row">
                                <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                                    <!-- Image -->
                                    <div class="bg-image hover-overlay hover-zoom ripple rounded" data-mdb-ripple-color="light">
                                    <?php $arrfoto=(array)json_decode($data->foto,true);  ?>
                                        @foreach ($arrfoto as $value)
                                            @if ($loop->first)
                                                <?php $pic1=$value  ?>
                                            @endif
                                        @endforeach
                                    <img src="{{ url('fotoproduk')}}/{{ $pic1 }}" class="w-100"/>
                                    </div>
                                    <!-- Image -->
                                </div>

                                <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                                    <!-- Data -->
                                    <p><strong>{{ ucwords($data->nama_produk) }}</strong></p>
                                    <p>Warna : {{ $data->nama_warna }}</p>
                                    <p>Bahan : {{ $data->nama_bahan }}</p>
                                    
                                    @if ($data->tambahan_motif === 0)
                                    <p><strong>*dengan tambahan motif</strong></p>
                                    @endif

                                    <a href="/deletekeranjang?id_produk={{ $data->id_produk }}&nama_warna={{ $data->nama_warna }}&nama_bahan={{ $data->nama_bahan }}&harga_produk={{ $data->harga_produk }}">
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin ingin menghapus produk ini dari keranjang?');">
                                            <i class="fa fa-trash p-1" aria-hidden="true"></i>
                                        </button>
                                    </a>
                                    <!-- Data -->
                                </div>

                                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                                    <!-- Quantity -->
                                    <div class="d-flex mb-4" style="max-width: 150px; align-items: center; justify-content: center;">
                                        <p class="text-center">Jumlah : <strong>{{ $data->jumlah }}</strong></p>
                                    </div>
                                    <!-- Quantity -->

                                    <!-- Price -->
                                    <p class="text-start text-md-center">
                                    <strong>Rp. {{ number_format($data->harga_produk * $data->jumlah) }}</strong>
                                    </p>
                                    <!-- Price -->
                                </div>
                                </div>
                                <!-- Single item -->

                                <hr class="my-4" />
                            @endforeach
                                <label for="notes" class="form-label">Catatan Pesanan</label>
                                <textarea name="notes" id="notes" rows="8" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                            <!-- product card downn -->
                            <!-- hitung semua jumlah belanja -->
                            @php
                            $totalPrice = 0;
                            $totalWeight = 100;
                            $dataArray = [];
                            @endphp

                            @foreach($uniqueV as $data)
                                @php
                                $itemTotalPrice = $data->harga_produk * $data->jumlah;
                                $itemTotalWeight = $data->berat * $data->jumlah;
                                $totalPrice += $itemTotalPrice;
                                $totalWeight += $itemTotalWeight;

                                $item = [
                                    'id_produk' => $data->id_produk,
                                    'nama_warna' => $data->nama_warna,
                                    'nama_bahan' => $data->nama_bahan,
                                    'harga_produk' => $data->harga_produk,
                                    'jumlah' => $data->jumlah,
                                    'tambahan_motif' => $data->tambahan_motif
                                ];
                                array_push($dataArray, $item);
                                @endphp
                            @endforeach
                        </div>
                        </div>
                        <div class="card mb-4">
                        <div class="card-body">
                            <div class="col-span-6 sm:col-span-4 p-2">
                                <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700">Nama Penerima</label>
                                <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="nama_pelanggan" id="nama_pelanggan" placeholder="Nama Penerima" value="" required>
                            </div>
                            <div class="col-span-6 sm:col-span-4 p-2">
                                <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                                <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="no_hp" id="no_hp" placeholder="No. HP" value="" required>
                            </div>
                            <p class="mt-3"><strong>Pengiriman Barang</strong></p>
                            <p>Apakah barang anda ingin dikirim melalui ekspedisi atau mengambil barang langsung dari penjual?</p>
                            <div class="mb-2 ml-3 p-2">
                                <input type="radio" class="form-check-input" id="langsung" name="optradio" value="langsung" onclick="text(1)" checked>Ambil langsung dari penjual
                            </div>
                            <div class="mb-2 ml-3 p-2">
                                <input type="radio" class="form-check-input" id="ekspedisi" name="optradio" value="ekspedisi" onclick="text(0)">Ekspedisi
                            </div>
                        
                            <div id="tambahAlamat" class="mb-2 ml-3 p-2">
                            <p><strong>Alamat Pelanggan</strong></p>
                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="alamat" class="block text-sm font-medium text-gray-700">
                                        Alamat Lengkap
                                        </label>
                                        <div class="mt-1">
                                        <textarea name="alamat" id="alamat" rows="8" class="focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md shadow-sm" ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-2">
                                        <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                            {!! Form::select('provinsi', $provinsi, '', [
                                            'class' => 'form-control shadow-sm',
                                            'placeholder' => 'Pilih Provinsi',
                                            'id' => 'province_id',
                                            ]) !!}
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <div class="form-group p-2" id="form-kota">
                                            
                                        </div>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <div class="form-group p-2" id="form-kecamatan">
                                            
                                        </div>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-2">
                                        <label for="kode_pos" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                                        <input type="number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="kode_pos" id="kode_pos" placeholder="Kode Pos" value="" required>
                                    </div>
                                </div>
                            <div id="cekOngkir" class="mb-2 ml-3 p-2 mt-5" style="border-top: 1px solid grey;">
                            <p><strong>Cek Biaya Ongkir</strong></p>
                                <div class="mb-2 p-2">
                                    <p id="labelAsal"><strong>Kota Asal</strong></p>
                                    <input class="form-control" type="text" id="origin_city" name="origin_city" value="Kota Payakumbuh" disabled>
                                </div>
                                <div class="mb-2 p-2">
                                    <p id="labelTujuan"><strong>Kota/Kabupaten Tujuan (Berdasarkan data profil pelanggan)</strong></p>
                                    <input class="form-control" type="text" id="destination_city" name="destination_city" value="" disabled>
                                </div>
                                <div class="mb-2 p-2">
                                    <label for="courier" class="form-label">Courier</label>
                                        <select name="courier" id="courier" class="form-select">
                                            <option>Choose Courier</option>
                                            <option value="jne">JNE</option>
                                            <option value="pos">POS</option>
                                            <option value="tiki">TIKI</option>
                                        </select>
                                </div>
                                <div class="mb-2 p-2">
                                    <label for="weight" class="form-label">Weight (Gram)</label>
                                    <input type="number" name="weight" id="weight" class="form-control" value="{{ $totalWeight }}" disabled>
                                </div>
                                <div class="ml-3 p-2">
                                    <button class="btn btn-primary float-right" id="checkBtn" onclick="save_alamat_baru()">Check Ongkir</button>
                                </div>
                                <hr class="my-4" />
                                <div id="result" class="mt-3 d-none"></div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4">
                        <div class="card-header py-3">
                            <h5 class="mb-0">Total Belanja</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                Sub Total
                                <span id="subtotal">{{ number_format($totalPrice) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                Pengiriman
                                <span><strong id="pengirimanPH"></strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                Layanan Ekspedisi
                                <span><strong id="layananEkspedisiPH"></strong></span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                                <div>
                                <strong>Total belanja</strong>
                                </div>
                                <span><strong id="totalBelanjaPH">{{ $totalPrice }}</strong></span>
                            </li>
                            </ul>
                            <button type="button" id="checkoutBtn" class="btn btn-primary btn-lg btn-block" onclick="check_data()">
                            Tambah Pesanan
                            </button>
                        </div>
                        </div>
                    </div>
                    </div>
                    <!-- downn -->
                    </div>
                </div>
            </div>
        </div>
<script>
    $(document).ready(function(){
        text(1);
        let kirim_ekspedisi = false;
        $('body').on('change', '#province_id', function() {
            let id = $(this).val();
            let route = "{{ route('get.kota') }}";
            $.ajax({
                type: 'get',
                url: route,
                data: {
                province_id: id
                },
                success: function(data) {
                    $('#form-kota').html(data);
                    }
                })
                })
        $('body').on('change', '#city_id', function() {
            var kota = document.getElementById('city_id');
            var selectedOptionKota = kota.options[kota.selectedIndex];
            var selectedKota = selectedOptionKota.text;
            let id = $(this).val();
            let route = "{{ route('get.kecamatan') }}";
            $.ajax({
                type: 'get',
                url: route,
                data: {
                city_id: id
                },
                success: function(data) {
                $('#destination_city').val(selectedKota);
                $('#form-kecamatan').html(data);
                }
            })
        })
    });
        function text(x){
            let totalBiaya = 0;
            let totalBelanja =  0;
            if (x==0){
                console.log('Memilih kirim dengan ekspedisi');
                document.getElementById("cekOngkir").style.display="block";
                document.getElementById("tambahAlamat").style.display="block";
                kirim_ekspedisi = true;
            }else if (x==1){
                kirim_ekspedisi = false;
                totalBiaya = 0;
                $('#result').empty();
                var courierSelect = document.getElementById("courier");
                courierSelect.value = "Choose Courier";
                document.getElementById("cekOngkir").style.display="none";
                document.getElementById("tambahAlamat").style.display="none";
            }
            totalBelanja = {{ $totalPrice }} + totalBiaya;
            let formattedTotalBelanja = totalBelanja.toLocaleString();

            $("#pengirimanPH").text(totalBiaya);
            $("#totalBelanjaPH").text(formattedTotalBelanja);
            return;
        }
</script>
<script>
    function save_alamat_baru(){
        let c = document.getElementById('alamat').value;
        let d = document.getElementById('province_id');
        var kota = document.getElementById('city_id');
        let kecamatan = document.getElementById('kecamatan_id');
        let g = document.getElementById('kode_pos').value;
        var selectedOptionKota = kota.options[kota.selectedIndex];
        var selectedOptionKecamatan = kecamatan.options[kecamatan.selectedIndex];
        var selectedOptionProvinsi = d.options[d.selectedIndex];
        var selectedKota = selectedOptionKota.text;
        var selectedKecamatan = selectedOptionKecamatan.text;
        var selectedProvinsi = selectedOptionProvinsi.text;

        if (kota !== null && kota !== undefined) {
            var e = kota.value;
        }
        if (kecamatan !== null && kecamatan !== undefined) {
            var f = kecamatan.value;
        }

        if (c == ""){
            alert("Kolom Alamat harus diisi");
            valid = false;
        }else if (d == ""){
            alert("Harap pilih provinsi tempat anda tinggal");
            valid = false;
        }
        else if (e == ""){
            alert("Harap pilih kota/kabupaten tempat anda tinggal");
            valid = false;
        }
        else if (f == ""){
            alert("Harap pilih kecamatan tempat anda tinggal");
            valid = false;
        }
        else if (g == ""){
            alert("Kolom Kode Pos harus diisi");
            valid = false;
        }
        else {
            console.log('data sudah lengkap');
        }
    }
</script>
<script>
    $('#checkBtn').on('click', function(e){
                    e.preventDefault();
                    $("#pengirimanPH").text(0);
                    $("#layananEkspedisiPH").text('');
                    let origin = '446' //id kota pyk pada database rajaongkir
                    let destination = $('#destination_city').val();
                    let courier = $('#courier').val();
                    let weight = $('#weight').val();

                    if (courier == 'Choose Courier'){
                        alert('Harap pilih kurir pengiriman');
                    }else if (destination == ''){
                        alert('Kota tujuan belum diisi');
                    }else { 
                    $.ajax({
                        url: "{{ route('check-ongkir') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                            origin: origin,
                            destination: destination,
                            courier: courier,
                            weight: weight
                        },
                        beforeSend: function(){
                            $('#checkBtn').html('Loading...');
                            $('#checkBtn').attr('disabled', true);
                        },
                        success: function(response){
                            $('#result').removeClass('d-none');
                            $('#checkBtn').html('Check Ongkir');
                            $('#checkBtn').attr('disabled', false);
                            $('#result').empty();
                            $('#result').append(`
                                <div class="col-12">
                                    <div class="card border rounded shadow">
                                        <div class="card-body">
                                        <p><strong>Silahkan pilih layanan yang ingin anda gunakan</strong></p>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Layanan</th>
                                                        <th>Deskripsi</th>
                                                        <th>Biaya</th>
                                                        <th>Waktu Pengiriman</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="resultBody">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            `);
                            $.each(response, function(i, val){
                                var biayaOngkir = val.cost[0].value.toLocaleString();
                                $('#resultBody').append(`
                                    <tr>
                                        <td><input type="radio" name="jenis_layanan" value="${i}"></td>
                                        <td>${val.service}</td>
                                        <td>${val.description}</td>
                                        <td>${biayaOngkir}</td>
                                        <td>${val.cost[0].etd}</td>
                                    </tr>
                                `);
                            });
                            $('input[type="radio"]').change(function() {
                                if ($(this).is(':checked')) {
                                    var selectedValue = $(this).val();
                                    var costValue = response[selectedValue].cost[0].value;
                                    var ekspedisiValue = response[selectedValue].service;
                                    let formattedCost = costValue.toLocaleString();
                                    totalBelanja = {{ $totalPrice }} + costValue;
                                    let formattedTotalBelanja = totalBelanja.toLocaleString();
                                    $("#layananEkspedisiPH").text(courier.toUpperCase() + ' - ' + ekspedisiValue.toUpperCase());
                                    $("#pengirimanPH").text(formattedCost);
                                    $("#totalBelanjaPH").text(formattedTotalBelanja);
                                }
                            });
                        },
                        error: function(xhr){
                            alert("Gagal Cek Biaya Ongkir");
                            console.log('gagal cek data ongkir');
                            console.log(xhr.responseText);
                        }
                    });
                } //close else
                });
</script>
<script>
    function check_data(){
        let biayaOngkir = false;
        var namaPel = $("#nama_pelanggan").val();
        var nopel = $("#no_hp").val();
        var totalBelanjaValue = $("#totalBelanjaPH").text();
        var subTotalValue = $("#subtotal").text();
            // cek apakah ekspedisi sudah dipilih jika radio ekspedisi terpilih
            if (kirim_ekspedisi == false){
                console.log("gapake ekspedisi");
                biayaOngkir = true;
            }else if (kirim_ekspedisi == true){
                console.log("pake ekspedisi");
                var pengirimanPHValue = $("#pengirimanPH").text();
                if (pengirimanPHValue == 0){
                    biayaOngkir = false;
                    alert("Pilih ekspedisi pengiriman")
                }else {
                    biayaOngkir = true;
                }
            }

        if (biayaOngkir == false){
            console.log('tidak aman untuk co');
        }else if (totalBelanjaValue == 0 || subTotalValue == 0){
            alert("Tidak ada barang yang akan dicheckout");
        }else if (namaPel == "" || nopel == ""){
            alert("Harap isi nama penerima dan no. HP");
        }
        else {
            console.log('aman untuk co');
            checkout();
        }
    }
</script>
<script>
    function checkout(){
        var dataArray = @json($dataArray);
        let total_belanja = $('#totalBelanjaPH').text();
        let biaya_ongkir = $('#pengirimanPH').text();
        let layanan_ekspedisi = $('#layananEkspedisiPH').text();
        let note_transaksi = $('#notes').val();

        var namaPel = $("#nama_pelanggan").val();
        var nopel = $("#no_hp").val();

        var alamat = '';
        var kecamatan = '';
        var kota = '';
        var provinsi = '';
        var kode_pos = '';
        
        if (!kirim_ekspedisi) {
            kirim_ekspedisi = 0,
            layanan_ekspedisi = '',
            biaya_ongkir = 0,
            alamat = '';
            kecamatan = '';
            kota = '';
            provinsi = '';
            kode_pos = '';
        } else {
            kirim_ekspedisi = 1;
            alamat = document.getElementById('alamat').val();
            d = document.getElementById('province_id');
            get_kota = document.getElementById('city_id');
            get_kecamatan = document.getElementById('kecamatan_id');
            kode_pos = document.getElementById('kode_pos').val();
            selectedOptionKota = get_kota.options[get_kota.selectedIndex];
            selectedOptionKecamatan = get_kecamatan.options[get_kecamatan.selectedIndex];
            selectedOptionProvinsi = d.options[d.selectedIndex];
            kota = selectedOptionKota.text;
            kecamatan = selectedOptionKecamatan.text;
            provinsi = selectedOptionProvinsi.text;
        }

        biaya_ongkir = String(biaya_ongkir);
        let formattedTotalBelanja = total_belanja.replace(/,(?=\d{3}(?!\d))/g, '');
        let formattedBiayaOngkir = biaya_ongkir.replace(/,(?=\d{3}(?!\d))/g, '');
        console.log(typeof(nopel), typeof(namaPel));
        $.ajax({
            url: "{{ route('insert_pesanan') }}",
            type: 'POST',
            dataType: 'json',
            data: {
            _token: "{{ csrf_token() }}",
            dataArray: dataArray,
            nama_pelanggan : namaPel,
            no_hp : nopel,
            total_belanja: formattedTotalBelanja,
            alamat: alamat,
            kecamatan: kecamatan,
            kota: kota,
            provinsi: provinsi,
            kode_pos: kode_pos,
            note_transaksi: note_transaksi,
            kirim_ekspedisi: kirim_ekspedisi,
            biaya_ongkir:formattedBiayaOngkir,
            layanan_ekspedisi:layanan_ekspedisi
            },
            beforeSend: function(){
                $('#checkoutBtn').html('Loading...');
                $('#checkoutBtn').attr('disabled', true);
            },
            success: function (response) {
                if (response.statusCode === 200) {
                    alert("Berhasil menambahkan pesanan");
                    location.href = "/daftarpesananadm";
                    console.log(response.data);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $('#validation-errors').html('');
                    $.each(errors, function (key, value) {
                        $('#validation-errors').append('<p>' + value + '</p>');
                    });
                } else {
                    alert("Gagal menambahkan pesanan");
                    console.log(xhr.responseText);
                }
            },
            complete: function () {
                $('#checkoutBtn').html('Checkout');
                $('#checkoutBtn').attr('disabled', false);
            }
        });
    }
</script>
@endsection
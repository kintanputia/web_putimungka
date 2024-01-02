@extends('layouts/navadmin')
@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- up -->
                        <form method="post" name="frm_add" id="frm_add" action="/addproduct" enctype="multipart/form-data" onsubmit="validasi()">
                            @csrf
                                <div class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    <b>Tambah Produk Kerajinan</b>
                                    </h3>
                                    <div class="grid grid-cols-6 gap-6">

                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="nama_produk" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                                        <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md form-control" name="nama_produk" id="nama_produk" placeholder="Nama Produk" value="{{ old('nama_produk') }}" required>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="nama_motif" class="block text-sm font-medium text-gray-700">Nama Motif</label>
                                        <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="nama_motif" id="nama_motif" placeholder="Nama Motif" value="{{ old('nama_motif') }}" required>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                                        <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="model" id="model" placeholder="Model" value="{{ old('model') }}" required>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="model" class="block text-sm font-medium text-gray-700">Warna</label>
                                        <table id="tabel_warna"  class="table table-borderless table-sm">
                                            <tr>
                                                <td>
                                                    Tambah Warna
                                                </td>  
                                                <td><button type="button" name="addw" id="addw" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="model" class="block text-sm font-medium text-gray-700">Bahan</label>
                                        <table id="tabel_bahan"  class="table table-borderless table-sm">
                                            <tr>
                                                <td>
                                                    Tambah Bahan
                                                </td>  
                                                <td><button type="button" name="addb" id="addb" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="jenis_jahitan" class="block text-sm font-medium text-gray-700">
                                        Jenis Jahitan
                                        </label>
                                        <div class="mt-1">
                                            <select name="jenis_jahitan" id="jenis_jahitan" class="form control mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                                <option value="" disabled selected>Pilih Jenis Jahitan</option>
                                                <option value="sulam">Sulam</option>
                                                <option value="bordir">Bordir</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                                        <input type="number" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="harga" id="harga" placeholder="Harga" value="{{ old('harga') }}" required>
                                    </div> -->
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="ukuran" class="block text-sm font-medium text-gray-700">Ukuran (cm)</label>
                                        <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="ukuran" id="ukuran" placeholder="Contoh : 120 x 80" value="{{ old('ukuran') }}" required>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="berat" class="block text-sm font-medium text-gray-700">Berat (gram)</label>
                                        <input type="number" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="berat" id="berat" placeholder="Berat" value="{{ old('berat') }}" required>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="waktu_pengerjaan" class="block text-sm font-medium text-gray-700">Waktu Pengerjaan (hari)</label>
                                        <input type="number" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="waktu_pengerjaan" id="waktu_pengerjaan" placeholder="Waktu Pengerjaan" value="{{ old('waktu_pengerjaan') }}">
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 py-1 px-3">
                                        <label for="foto" class="block text-sm font-medium text-gray-700">Foto Utama</label>
                                        <input type="file" name="foto[]">
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 py-1 px-3">
                                        <label for="foto" class="block text-sm font-medium text-gray-700">Foto Lainnya (Pilih seluruh foto yang akan diupload sekaligus)</label>
                                        <input type="file" name="foto[]" multiple>
                                    </div>
                                    <div class="col-span-6 p-3">
                                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                                        Deskripsi
                                        </label>
                                        <div class="mt-1">
                                        <textarea name="deskripsi" id="deskripsi" rows="8" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" >{{ old('deskripsi') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                                <button type="button" id="btn_submit" class="btn btn-primary" onclick="validasi()" >Simpan</button>
                            </div>
                            </div>
                        </form>
                        <!-- down -->
                    </div>
                </div>
            </div>
        </div>

<!-- script js -->
<!-- validasi isi form -->
<script type="text/javascript">
        function validasi(){
            let a = document.forms["frm_add"]["nama_produk"].value;
            let c = document.forms["frm_add"]["ukuran"].value;
            let d = document.forms["frm_add"]["berat"].value;
            let e = document.forms["frm_add"]["nama_motif"].value;
            let f = document.forms["frm_add"]["jenis_jahitan"].value;
            let g = document.forms["frm_add"]["model"].value;
            let i = document.forms["frm_add"]["deskripsi"].value;
            let j = document.forms["frm_add"]["waktu_pengerjaan"].value;
            let arw = document.getElementsByName('warna[]');
            let arb = document.getElementsByName('bahan[]');
            var foto = document.querySelectorAll('input[name="foto[]"]');
            var h = false;

            if (foto[0].files.length > 0) {
                h = true;
            }

            if (a == "") {
                alert("Nama Produk harus diisi");
            }
            else if (e == ""){
                alert("Kolom Nama Motif harus diisi");
                valid = false;
            }
            else if (g == ""){
                alert("Kolom Model harus diisi");
                valid = false;
            }
            else if (arw.length === 0) {
                alert("Harap pilih warna produk");
                valid = false;
            }
            else if (arb.length === 0) {
                alert("Harap pilih bahan produk");
                valid = false;
            }
            else if (f == ""){
                alert("Harap pilih salah satu jenis jahitan");
                valid = false;
            }
            else if (c == ""){
                alert("Kolom Ukuran harus diisi");
                valid = false;
            }
            else if (d == ""){
                alert("Kolom Berat harus diisi");
                valid = false;
            }
            else if (d <= 0 ){
                alert("Kolom Berat harus lebih besar daripada 0");
                valid = false;
            }
            else if (j == ""){
                alert("Kolom waktu pengerjaan harus diisi");
                valid = false;
            }
            else if (j <= 0 ){
                alert("Kolom waktu pengerjaan harus lebih besar daripada 0");
                valid = false;
            }
            else if (!h){
                alert("Harap Upload Foto Produk");
                valid = false;
            }
            else if (i == ""){
                alert("Kolom Deskrpsi harus diisi");
                valid = false;
            }
            else {
                // set value dropdown terkakhir jika belum disable
                var indw = arw.length;
                let wrn = document.getElementsByName('warna[]')[indw - 1].value;
                var indb = arb.length;
                let bhn = document.getElementsByName('bahan[]')[indb - 1].value;
                let harga_bhn = document.getElementsByName('harga_bahan[]')[indb - 1].value;
                if (wrn !== null && wrn !== ''){
                    const lastWarna = selectedColors[selectedColors.length - 1];
                    const comparisonWarna = wrn;
                    if (lastWarna != comparisonWarna) {
                        selectedColors.push(wrn);
                    }
                }
                if (bhn !== null && bhn !== ''){
                    selectedMaterial.push({
                                id: bhn,
                                harga_bahan: harga_bhn
                            });
                    const lastBahan = selectedMaterial[selectedMaterial.length - 1].id;
                    const comparisonBahan = bhn;
                    console.log('bahan terakhir dalam array', lastBahan);
                    console.log('bahan terakhir dalam input', comparisonBahan);
                    if (lastBahan != comparisonBahan) {
                        selectedMaterial.pop();//mengeluarkan value array terakhir
                        selectedMaterial.push({
                                id: bhn,
                                harga_bahan: harga_bhn
                            });
                    }
                }

                // cek apakah array warna dan bahan kosong
                if (selectedColors.length === 0){
                    alert("Harap pilih warna produk");
                    valid = false;
                }
                else if (selectedMaterial.length === 0){
                    alert("Harap pilih bahan produk");
                    valid = false;
                }
                else {
                    // update seluruh harga bahan
                    $('#tabel_bahan tr').each(function () {
                        var row = $(this);
                        var selectB = row.find('select');
                        var hargaInput = row.find('input[name="harga_bahan[]"]');
                        var selectedId = selectB.val();
                        var hargaValue = hargaInput.val();

                        var existingMaterial = selectedMaterial.find(material => material.id == selectedId);
                        if (existingMaterial) {
                            existingMaterial.harga_bahan = hargaValue;
                        }
                    });

                    // convert isi obj -> int
                    selectedMaterial.forEach(function(item) {
                        item.id = parseInt(item.id, 10);
                        item.harga_bahan = parseInt(item.harga_bahan, 10);
                    });
                    // filter duplikat bahan dan harga
                    selectedMaterial = selectedMaterial.filter((item, index, self) =>
                        index === self.findIndex((t) =>
                            t.id === item.id && t.harga_bahan === item.harga_bahan
                        )
                    );

                    // cek harga minus
                    var aman = true;
                    for (let i = 0; i < selectedMaterial.length; i++) {
                        if (selectedMaterial[i].harga_bahan < 0) {
                            alert("Harga bahan produk tidak boleh negatif");
                            valid = false;
                            aman  = false;
                            selectedMaterial[i].harga_bahan = 0;
                            // selectedMaterial.splice(i, 1);
                            // i--;
                        }
                    }
                    console.log('bahan',selectedMaterial);
                    console.log('warna',selectedColors);
                    if (aman) {
                        submitForm();
                    }
                }
            }
        }
</script>
<!-- submit form -->
<script>
    function submitForm(){
            var jsonBahan = JSON.stringify(selectedMaterial);//ubah jd json
            var form = $("#frm_add");
            var forms = $('#frm_add')[0];
            var url = form.attr('action');
            var formData = new FormData(forms);
            console.log('warna terpilih : ', selectedColors);
            console.log('bahan terpilih : ',selectedMaterial);
            formData.append('selectedColors', selectedColors);
            formData.append('selectedMaterial', jsonBahan);
            $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            url: url,
            data:formData,
            success: function () {
                alert("Produk Berhasil Ditambahkan ke Katalog");
                location.href = "/katalogadmin";
            },
            error: function (xhr, status, error) {
                var errorMessage = "Gagal";
                if (xhr.responseText) {
                    errorMessage = xhr.responseText;
                }
                console.log(errorMessage);
                alert("Produk Gagal Ditambahkan ke Katalog");
            }
        });
        }
</script>
<!-- dynamic add button -->
<script>
    // warna
    var selectedColors = [];
    var i = 0;
    var j = 1;
    $('#addw').click(function () {
        i++;
        j++;
        var d = @json($warna);

        var warnat = $('#warna'+i+' :selected').text();
        d.forEach(function (warna) {
            if (warnat === warna.nama_warna) {
                selectedColors.push(warna.id); //bahan terpilih masuk array
            }
        });
        
        // hapus warna terpilih
        var availableColors = d.filter(item => !selectedColors.includes(item.id));
        console.log(selectedColors);
        console.log(availableColors);
        

        var newRowWarna = '<tr class="dynamic-added" id="row-warna' + i + '">';
        newRowWarna += '<td><select class="form-control default-select warna-dropdown" id="warna' + j + '" name="warna[]" data-size="5">';
        newRowWarna += '<option value="" selected>Pilih Warna</option>';
        availableColors.forEach(function (color) {
            if (!selectedColors.includes(color.id)) {
                newRowWarna += '<option value="' + color.id + '">' + color.nama_warna + '</option>';
            }
        });
        
        newRowWarna += '</select></td>';
        newRowWarna += '<td><button type="button" name="remove" data-rowid="' + i + '" data-table="warna" class="btn btn-danger btn_remove btn-sm">';
        newRowWarna += '<i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>';

        $('#tabel_warna').append(newRowWarna);
        $('#warna' + i).prop('disabled', true);//disable dropdown yang sudah terpilih
    });
    // tombol hapus
    $('#tabel_warna').on('click', '.btn_remove', function () {
        var tablew= $(this).data('table');
        var t = @json($warna);
        var rowIdW = $(this).data('rowid');
        var ridW = rowIdW + 1;
        var hapusw = $('#warna'+ridW+' :selected').text();
        console.log(tablew);
        
        // hapus warna terpilih dari array
        if (tablew === 'warna'){
        t.forEach(function (h) {
            if (hapusw === h.nama_warna) {
                selectedColors = selectedColors.filter(item => item !== h.id);
            }
        });
        $('#row-warna' + rowIdW).remove();
        }
    });
    // bahan  
    var selectedMaterial = [];
    var x = 2;
    var y = 3;

    $('#addb').click(function () {
        x++;
        y++;
        var e = @json($bahan);
        
        // hapus bahan terpilih
        var availableMaterial = e.filter(item => !selectedMaterial.some(selected => selected.id === item.id));

        var newRowBahan = '<tr class="dynamic-added" id="row-bahan' + x + '">';
        newRowBahan += '<td><select class="form-control default-select bahan-dropdown" id="bahan' + y + '" name="bahan[]" data-size="5">';
        newRowBahan += '<option value="" selected>Pilih Bahan</option>';
        availableMaterial.forEach(function (material) {
            newRowBahan += '<option value="' + material.id + '">' + material.nama_bahan + '</option>';
        });
        
        newRowBahan += '</select></td>';
        newRowBahan += '<td><input type="number" class="form-control" name="harga_bahan[]" id="harga_bahan' + y + '" placeholder="Harga"></td>';
        newRowBahan += '<td><button type="button" name="remove" data-rowid="' + x + '" data-table="bahan" class="btn btn-danger btn_remove btn-sm">';
        newRowBahan += '<i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>';

        var lastHarga = $('#harga_bahan' + x).val();
        var bahant = $('#bahan'+x+' :selected').text();
        e.forEach(function (bahan) {
            if (bahant === bahan.nama_bahan) {
                selectedMaterial.push({
                    id: bahan.id,
                    harga_bahan: lastHarga
                });
            }
        });

        $('#tabel_bahan').append(newRowBahan);

        $('#bahan' + x).prop('disabled', true); // disable dropdown yang sudah terpilih

        // filter bahan yang sudah terpilih
        availableMaterial = e.filter(item => !selectedMaterial.some(selected => selected.id === item.id));

        // Update dropdown bahan
        $('#bahan' + y).empty().append('<option value="" selected>Pilih Bahan</option>');
        availableMaterial.forEach(function (material) {
            $('#bahan' + y).append('<option value="' + material.id + '">' + material.nama_bahan + '</option>');
        });
    });
    // tombol hapus
    $('#tabel_bahan').on('click', '.btn_remove', function () {
        var tableb= $(this).data('table');
        var u = @json($bahan);
        var rowIdB = $(this).data('rowid');
        var ridB = rowIdB + 1;
        var hapusb = $('#bahan'+ridB+' :selected').text();
        console.log(tableb);
        
        // hapus warna terpilih dari array
        if (tableb === 'bahan'){
        u.forEach(function (p) {
            if (hapusb === p.nama_bahan) {
                // selectedMaterial = selectedMaterial.filter(item => item !== p.id);
                selectedMaterial = selectedMaterial.filter(item => item.id !== p.id);
            }
        });
        $('#row-bahan' + rowIdB).remove();
        }
    });  
</script>
@endsection
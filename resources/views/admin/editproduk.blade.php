@extends('layouts/navadmin')
@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- uppp -->
                        <form method="post" name="frm_edit" id="frm_edit" action="/editproduk_process" enctype="multipart/form-data" onsubmit="validasi()">
                            @csrf
                                <div class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    <b>Edit Produk Kerajinan</b>
                                    </h3>
                                    <div class="grid grid-cols-6 gap-6">
                                    <input type="hidden" name="id" value="{{ $detail->id }}">
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="nama_produk" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                                        <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md form-control" name="nama_produk" id="nama_produk" placeholder="Nama Produk" value="{{ $detail->nama_produk }}" required>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="nama_motif" class="block text-sm font-medium text-gray-700">Nama Motif</label>
                                        <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="nama_motif" id="nama_motif" placeholder="Nama Motif" value="{{ $detail->nama_motif }}" required>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                                        <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="model" id="model" placeholder="Model" value="{{ $detail->model }}" required>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="model" class="block text-sm font-medium text-gray-700">Warna</label>
                                        <table id="tabel_warna"  class="table table-borderless table-sm">
                                            <tr>
                                                <td></td>
                                                <td><button type="button" name="addw" id="addw" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button></td>
                                            </tr>
                                            @foreach ($warna as $key => $w)
                                            <tr id="row-warna{{ $key }}">
                                                <td>
                                                    <select class="form-control default-select warna-dropdown" id="warna{{ $key }}" name="warna[]" data-size="5" disabled>
                                                    <option value="{{ $w->id_warna }}" selected>{{ $w->nama_warna }}</option>
                                                    </select>
                                                </td>
                                                <td><button type="button" name="remove" data-rowid="{{ $key }}" data-table="warna" class="btn btn-danger btn_remove btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="model" class="block text-sm font-medium text-gray-700">Bahan</label>
                                        <table id="tabel_bahan"  class="table table-borderless table-sm">
                                            <tr>
                                                <td></td>
                                                <td><button type="button" name="addb" id="addb" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button></td>
                                            </tr>
                                            @foreach ($bahan as $key => $b)
                                            <tr id="row-bahan{{ $key }}">
                                                <td>
                                                    <select class="form-control default-select bahan-dropdown" id="bahan{{ $key }}" name="bahan[]" data-size="5" disabled>
                                                    <option value="{{ $b->id_bahan }}" selected>{{ $b->nama_bahan }}</option>
                                                    </select>
                                                </td>
                                                <td><button type="button" name="remove" data-rowid="{{ $key }}" data-table="bahan" class="btn btn-danger btn_remove btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="jenis_jahitan" class="block text-sm font-medium text-gray-700">
                                        Jenis Jahitan
                                        </label>
                                        <div class="mt-1">
                                            <select name="jenis_jahitan" id="jenis_jahitan" class="form control mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                                <?php 
                                                if ($detail->jenis_jahitan == 'sulam'){?>
                                                    <option value="sulam" selected>Sulam</option>
                                                    <option value="bordir">Bordir</option>
                                                <?php } 
                                                else if ($detail->jenis_jahitan == 'bordir'){ ?>
                                                    <option value="bordir" selected>Bordir</option>
                                                    <option value="sulam">Sulam</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                                        <input type="number" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="harga" id="harga" placeholder="Harga" value="{{ $detail->harga }}" required>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="ukuran" class="block text-sm font-medium text-gray-700">Ukuran (cm)</label>
                                        <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="ukuran" id="ukuran" placeholder="Contoh : 120 x 80" value="{{ $detail->ukuran }}" required>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="berat" class="block text-sm font-medium text-gray-700">Berat (gram)</label>
                                        <input type="number" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="berat" id="berat" placeholder="Berat" value="{{ $detail->berat }}" required>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="waktu_pengerjaan" class="block text-sm font-medium text-gray-700">Waktu Pengerjaan (hari) *opsional</label>
                                        <input type="number" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="waktu_pengerjaan" id="waktu_pengerjaan" placeholder="Waktu Pengerjaan" value="{{ $detail->waktu_pengerjaan }}">
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 py-1 px-3">
                                        <?php $arrfoto=(array)json_decode($detail->foto,true);  ?>
                                        @foreach ($arrfoto as $value)
                                            @if ($loop->first)
                                                <?php $pic1=$value  ?>
                                            @endif
                                        @endforeach
                                        <?php  
                                            $arrfotolain = array_filter($arrfoto, function ($item) use ($pic1) {
                                            return $item !== $pic1;
                                        });
                                        ?>
                                        <p>Note : Jika ingin menambah, menghapus, atau mengubah foto harap upload ulang seluruh foto</p>
                                        <label for="foto" class="block text-sm font-medium text-gray-700">Foto Utama</label>
                                        <img src="{{ url('fotoproduk/' . $pic1) }}" alt="Preview" width="100" height="100" class="mb-2">
                                        <input type="file" name="foto[]">
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 py-1 px-3">
                                        <label for="foto" class="block text-sm font-medium text-gray-700">Foto Lainnya (Pilih seluruh foto yang akan diupload sekaligus)</label>
                                            @foreach ($arrfotolain as $f)
                                            <img src="{{ url('fotoproduk/' . $f) }}" alt="Preview" width="100" height="100" class="mb-2" style="display: inline-block; margin-right: 10px;">
                                            @endforeach
                                        <input type="file" name="foto[]" multiple>
                                    </div>
                                    <div class="col-span-6 p-3">
                                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                                        Deskripsi
                                        </label>
                                        <div class="mt-1">
                                        <textarea name="deskripsi" id="deskripsi" rows="8" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" >{{ $detail->deskripsi }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <button type="button" id="btn_submit" class="btn btn-primary" onclick="validasi()">Simpan</button>
                            </div>
                            </div>
                        </form>
                        <!-- down -->
                    </div>
                </div>
            </div>
        </div>


<script>
    // warna
    var selectedColors = [];
    var jsonW = @json($warna);
    var w = jsonW.map(function(item) {
        return item.id_warna;
    });
    // set selectedColors dengan warna yang sudah terpilih
    $(document).ready(function(){
        for (var s = 0; s < w.length; s++) {
            selectedColors.push(w[s]);
        }
    });
    var i = w.length + 1;
    var j = w.length + 2;
    $('#addw').click(function () {
        i++;
        j++;
        var d = @json($allColors);
        
        var warnat = $('#warna'+i+' :selected').text();
        d.forEach(function (warna) {
            if (warnat === warna.nama_warna) {
                selectedColors.push(warna.id); //bahan terpilih masuk array
            }
        });
        console.log(selectedColors);
        
        // hapus warna terpilih
        var availableMaterials = d.filter(item => !selectedColors.includes(item.id));

        var newRowWarna = '<tr class="dynamic-added" id="row-warna' + i + '">';
        newRowWarna += '<td><select class="form-control default-select warna-dropdown" id="warna' + j + '" name="warna[]" data-size="5">';
        newRowWarna += '<option value="" selected>Pilih Warna</option>';
        availableMaterials.forEach(function (color) {
            if (!selectedColors.includes(color.id)) {
                newRowWarna += '<option value="' + color.id + '">' + color.nama_warna + '</option>';
            }
        });
        
        newRowWarna += '</select></td>';
        newRowWarna += '<td><button type="button" name="remove" data-rowid="' + j + '" data-table="warna" class="btn btn-danger btn_remove btn-sm">';
        newRowWarna += '<i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>';

        $('#tabel_warna').append(newRowWarna);
        $('#warna' + i).prop('disabled', true);//disable dropdown yang sudah terpilih
    });
    // tombol hapus
    $('#tabel_warna').on('click', '.btn_remove', function () {
        var tablew= $(this).data('table');
        var t = @json($allColors);
        var rowIdW = $(this).data('rowid');
        var ridW = rowIdW;
        var rowW = $(this).closest('tr');
        var hapusw = $('#warna'+ridW+' :selected').text();
        console.log(hapusw);
        
        // hapus warna terpilih dari array
        if (tablew === 'warna'){
        t.forEach(function (h) {
            if (hapusw === h.nama_warna) {
                // selectedColors = selectedColors.filter(item => item !== h.id);
                console.log("Before removal: selectedColors =", selectedColors);
                selectedColors = selectedColors.filter(item => item !== h.id);
                console.log("After removal: selectedColors =", selectedColors);
            }
        });
        // $('#row-warna' + rowIdW).remove();
        rowW.remove();
        }
    });
    // bahan
    var selectedMaterials = [];
    var jsonB = @json($bahan);
    var b = jsonB.map(function(item) {
        return item.id_bahan;
    });
    // set selectedMaterials dengan bahan yang sudah terpilih
    $(document).ready(function(){
        for (var s = 0; s < b.length; s++) {
            selectedMaterials.push(b[s]);
        }
    });
    var g = b.length + 1;
    var h = b.length + 2;
    $('#addb').click(function () {
        g++;
        h++;
        var d = @json($allMaterials);
        
        var bahant = $('#bahan'+g+' :selected').text();
        d.forEach(function (bahan) {
            if (bahant === bahan.nama_bahan) {
                selectedMaterials.push(bahan.id); //bahan terpilih masuk array
            }
        });
        console.log(selectedMaterials);
        
        // hapus bahan terpilih
        var availableMaterials = d.filter(item => !selectedMaterials.includes(item.id));

        var newRowBahan = '<tr class="dynamic-added" id="row-bahan' + g + '">';
        newRowBahan += '<td><select class="form-control default-select bahan-dropdown" id="bahan' + h + '" name="bahan[]" data-size="5">';
        newRowBahan += '<option value="" selected>Pilih Bahan</option>';
        availableMaterials.forEach(function (Material) {
            if (!selectedMaterials.includes(Material.id)) {
                newRowBahan += '<option value="' + Material.id + '">' + Material.nama_bahan + '</option>';
            }
        });
        
        newRowBahan += '</select></td>';
        newRowBahan += '<td><button type="button" name="remove" data-rowid="' + h + '" data-table="bahan" class="btn btn-danger btn_remove btn-sm">';
        newRowBahan += '<i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>';

        $('#tabel_bahan').append(newRowBahan);
        $('#bahan' + g).prop('disabled', true);//disable dropdown yang sudah terpilih
    });
    // tombol hapus
    $('#tabel_bahan').on('click', '.btn_remove', function () {
        var tableb= $(this).data('table');
        var o = @json($allMaterials);
        var rowIdB = $(this).data('rowid');
        var ridB = rowIdB;
        var rowB = $(this).closest('tr');
        var hapusb = $('#bahan'+ridB+' :selected').text();
        console.log(hapusb);
        
        // hapus bahan terpilih dari array
        if (tableb === 'bahan'){
        o.forEach(function (h) {
            if (hapusb === h.nama_bahan) {
                // selectedMaterials = selectedMaterials.filter(item => item !== h.id);
                console.log("Before removal: selectedMaterials =", selectedMaterials);
                selectedMaterials = selectedMaterials.filter(item => item !== h.id);
                console.log("After removal: selectedMaterials =", selectedMaterials);
            }
        });
        // $('#row-bahan' + rowIdW).remove();
        rowB.remove();
        }
    });
</script>
<script>
    function validasi(){
            let a = document.forms["frm_edit"]["nama_produk"].value;
            let b = document.forms["frm_edit"]["harga"].value;
            let c = document.forms["frm_edit"]["ukuran"].value;
            let d = document.forms["frm_edit"]["berat"].value;
            let e = document.forms["frm_edit"]["nama_motif"].value;
            let f = document.forms["frm_edit"]["jenis_jahitan"].value;
            let g = document.forms["frm_edit"]["model"].value;
            let h = document.getElementsByName('foto[]');
            let i = document.forms["frm_edit"]["deskripsi"].value;
            let arw = document.getElementsByName('warna[]');
            let arb = document.getElementsByName('bahan[]');

            if (arw.length === 0) {
                alert("Harap pilih warna produk");
                valid = false;
            }
            else if (arb.length === 0) {
                alert("Harap pilih bahan produk");
                valid = false;
            }
            else if (a == "") {
                alert("Nama Produk harus diisi");
            }
            else if (b == ""){
                alert("Kolom Harga harus diisi");
                valid = false;
            }
            else if (c == ""){
                alert("Kolom Ukuran harus dipilih");
                valid = false;
            }
            else if (d == ""){
                alert("Kolom Berat harus diisi");
                valid = false;
            }
            else if (e == ""){
                alert("Kolom Nama Motif harus diisi");
                valid = false;
            }
            else if (f == ""){
                alert("Kolom Jenis Jahitan harus diisi");
                valid = false;
            }
            else if (g == ""){
                alert("Kolom Model harus diisi");
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
                if (wrn !== null && wrn !== ''){
                    selectedColors.push(wrn);
                    const lastWarna = selectedColors[selectedColors.length - 1];
                    const comparisonWarna = wrn;
                    if (lastWarna !== comparisonWarna) {
                        selectedColors.pop();//mengeluarkan value array terakhir
                        selectedColors.push(wrn);
                    }
                }
                if (bhn !== null && bhn !== ''){
                    selectedMaterials.push(bhn);
                    const lastBahan = selectedMaterials[selectedMaterials.length - 1];
                    const comparisonBahan = bhn;
                    if (lastBahan !== comparisonBahan) {
                        selectedMaterials.pop();//mengeluarkan value array terakhir
                        selectedMaterials.push(bhn);
                    }
                }

                // cek apakah array warna dan bahan kosong
                if (selectedColors.length === 0){
                    alert("Harap pilih warna produk");
                    valid = false;
                }
                else if (selectedMaterials.length === 0){
                    alert("Harap pilih bahan produk");
                    valid = false;
                }
                else {
                    submitForm();
                }
            }
        }
</script>
<!-- submit form -->
<script>
    function submitForm(){
            var form = $("#frm_edit");
            var forms = $('#frm_edit')[0];
            var url = form.attr('action');
            var formData = new FormData(forms);
            console.log(selectedColors);
            console.log(selectedMaterials);
            formData.append('selectedColors', selectedColors);
            formData.append('selectedMaterial', selectedMaterials);
            console.log(formData);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                url: url,
                data:formData,
                success: function(response) {
                    alert("Data Berhasil Diedit");
                    location.href = "/katalogadmin";
                },
                error: function (xhr, status, error) {
                    var errorMessage = "Gagal";
                    if (xhr.responseText) {
                        errorMessage = xhr.responseText;
                    }
                    console.log(errorMessage);
                    alert("Data Gagal Diedit");
                }
            });
        }
</script>
@endsection
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form method="post" name="frm_addp" id="frm_addp" action="/addprofilpel" enctype="multipart/form-data" onsubmit="validasi()">
                            @csrf
                                <div class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    </h3>
                                    <div class="grid grid-cols-6 gap-6">
                                    <!-- hidden input untuk menampung id user -->
                                    <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                                        <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md form-control" name="nama_pelanggan" id="nama_pelanggan" placeholder="Nama Pelanggan" value="{{ Auth::user()->name }}" required>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4 p-3">
                                        <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                                        <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="no_hp" id="no_hp" placeholder="No. HP" value="{{ old('no_hp') }}" required>
                                    </div>
                                    <div class="col-span-6 p-3">
                                        <label for="alamat" class="block text-sm font-medium text-gray-700">
                                        Alamat Lengkap
                                        </label>
                                        <div class="mt-1">
                                        <textarea name="alamat" id="alamat" rows="8" class="focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md shadow-sm" >{{ old('alamat') }}</textarea>
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
                                        <input type="number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="kode_pos" id="kode_pos" placeholder="Kode Pos" value="{{ old('kode_pos') }}" required>
                                    </div>
                            </div>
                            <div class="px-4 py-3 text-right sm:px-6">
                                <button type="button" class="btn btn-primary" style="background-color: #2C73D2;" onclick="validasi()">Simpan</button>
                            </div>
                        </form>
            </div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
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
        let id = $(this).val();
        let route = "{{ route('get.kecamatan') }}";
        $.ajax({
            type: 'get',
            url: route,
            data: {
            city_id: id
            },
            success: function(data) {
            $('#form-kecamatan').html(data);
            }
        })
    })
})
</script>
<script>
    function validasi(){
        let a = document.forms["frm_addp"]["nama_pelanggan"].value;
        let b = document.forms["frm_addp"]["no_hp"].value;
        let c = document.forms["frm_addp"]["alamat"].value;
        let d = document.getElementById('province_id').value;
        let kota = document.getElementById('city_id');
        let kecamatan = document.getElementById('kecamatan_id');
        let g = document.forms["frm_addp"]["kode_pos"].value;
        
        // insert value kota dan kecamatan
        if (kota !== null && kota !== undefined) {
            var e = kota.value;
        }
        if (kecamatan !== null && kecamatan !== undefined) {
            var f = kecamatan.value;
        }

        // cek kelengkapan data
        if (a == ""){
            alert("Kolom Nama Pelanggan harus diisi");
            valid = false;
        }
        else if (b == ""){
            alert("Kolom No. HP harus diisi");
            valid = false;
        }
        else if (c == ""){
            alert("Kolom Alamat harus diisi");
            valid = false;
        }
        else if (d == ""){
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
            submitForm(d,e,f);
        }
    }
</script>
<script>
    function submitForm(d,e,f){
            var form = $("#frm_addp");
            var forms = $('#frm_addp')[0];
            var url = form.attr('action');
            var formData = new FormData(forms);
            formData.append('province_id', d);
            formData.append('city_id', e);
            formData.append('kecamatan_id', f);
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                url: url,
                data:formData,
                success: function () {
                    alert("Data Berhasil Ditambahkan");
                    location.href = "/profilpelanggan/{{Auth::user()->id}}";
                },
                error: function (xhr, status, error) {
                    var errorMessage = "Gagal";
                    if (xhr.responseText) {
                        errorMessage = xhr.responseText;
                    }
                    console.log(errorMessage);
                    alert("Data Gagal Ditambahkan");
                }
            });
        }
</script>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Profil Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- uppp -->
                    <form method="post" name="frm_editp" id="frm_editp" action="/editprofilp_process" enctype="multipart/form-data" onsubmit="validasi()">
                            @csrf
                                <div class="shadow overflow-hidden sm:rounded-md">
                                    <div class="px-4 py-5 bg-white sm:p-6">
                                        <div class="grid grid-cols-6 gap-6">
                                        <input type="hidden" name="id" id="id" value="{{ Auth::user()->id }}">
                                        <div class="col-span-6 sm:col-span-4 p-3">
                                            <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                                            <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md form-control" name="nama_pelanggan" id="nama_pelanggan" placeholder="Nama Pelanggan" value="{{ $data->nama_pelanggan }}" required>
                                        </div>
                                        <div class="col-span-6 sm:col-span-4 p-3">
                                            <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                                            <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="no_hp" id="no_hp" placeholder="No. HP" value="{{ $data->no_hp }}" required>
                                        </div>
                                        <div class="col-span-6 p-3">
                                            <label for="alamat" class="block text-sm font-medium text-gray-700">
                                            Alamat
                                            </label>
                                            <div class="mt-1">
                                            <textarea name="alamat" id="alamat" rows="8" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" >{{ $data->alamat }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-span-6 sm:col-span-4 p-3">
                                            <select class="form-control default-select" id="provinsi" name="provinsi">
                                                @foreach ($allProvinces as $p)
                                                    <option value="{{ $p->id }}" {{ $nama_provinsi == $p->id ? 'selected' : '' }}>
                                                        {{ $p->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-6 sm:col-span-4 p-3">
                                            <select class="form-control default-select" id="kota" name="kota">
                                                @foreach ($allCities as $k)
                                                    <option value="{{ $k->id }}" {{ $nama_kota == $k->id ? 'selected' : '' }}>
                                                        {{ $k->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-6 sm:col-span-4 p-3">
                                            <select class="form-control default-select" id="kecamatan" name="kecamatan">
                                                @foreach ($allKecamatan as $kc)
                                                    <option value="{{ $kc->id }}" {{ $nama_kecamatan == $kc->id ? 'selected' : '' }}>
                                                        {{ $kc->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-6 sm:col-span-4 p-3">
                                            <label for="kode_pos" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                                            <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="kode_pos" id="kode_pos" placeholder="Kode Pos" value="{{ $data->kode_pos }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                    <button type="button" id="btn_submit" class="btn btn-primary" style="background-color: #2C73D2;" onclick="validasi()">Simpan</button>
                                </div>
                            </div>
                        </form>
                    <!-- downn -->
                </div>
            </div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
    $('body').on('change', '#provinsi', function() {
        let id = $(this).val();
        let route = "{{ route('get.kota.edit') }}";
        $.ajax({
            type: 'get',
            url: route,
            data: {
            province_id: id
            },
            success: function(data) {
                console.log('Berhasil ambil data kota');
                $('#kota').empty();
                $('#kecamatan').empty();
                $('#kota').append($('<option>', {
                    value: '',
                    text: 'Pilih Kota/Kabupaten'
                }));
                $('#kecamatan').append($('<option>', {
                    value: '',
                    text: 'Pilih kecamatan'
                }));
                $.each(data, function(id, name) {
                    $('#kota').append($('<option>', {
                        value: id,
                        text: name
                    }));
                });
            },
            error: function() {
                console.log('Gagal ambil data kota');
            }
        })
    })
    $('body').on('change', '#kota', function() {
        let id = $(this).val();
        let route = "{{ route('get.kecamatan.edit') }}";
        $.ajax({
            type: 'get',
            url: route,
            data: {
            city_id: id
            },
            success: function(data) {
                console.log('Berhasil ambil data kecamatan');
                $('#kecamatan').empty();
                $('#kecamatan').append($('<option>', {
                    value: '',
                    text: 'Pilih kecamatan'
                }));
                $.each(data, function(id, name) {
                    $('#kecamatan').append($('<option>', {
                        value: id,
                        text: name
                    }));
                });
            },
            error: function() {
                console.log('Gagal ambil data kecamatan');
            }
        })
    })
})
</script>
<script>
    function validasi(){
        let a = document.forms["frm_editp"]["nama_pelanggan"].value;
        let b = document.forms["frm_editp"]["no_hp"].value;
        let c = document.forms["frm_editp"]["alamat"].value;
        let d = document.getElementById('provinsi').value;
        let kota = document.getElementById('kota');
        let kecamatan = document.getElementById('kecamatan');
        let g = document.forms["frm_editp"]["kode_pos"].value;
        
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
<!-- submit form -->
<script>
    function submitForm(d,e,f){
            var form = $("#frm_editp");
            var forms = $('#frm_editp')[0];
            var url = form.attr('action');
            var formData = new FormData(forms);
            formData.append('province_id', d);
            formData.append('city_id', e);
            formData.append('kecamatan_id', f);
            formData.forEach(function(value, key) {
                console.log(key, value);
            });
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
                    location.href = "/profilpelanggan/{{Auth::user()->id}}";
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
</x-app-layout>
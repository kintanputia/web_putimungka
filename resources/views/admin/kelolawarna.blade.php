@extends('layouts/navadmin')
@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="col-lg-4 mt-3 mb-3 d-flex p-1">
                            <input type="text" name="warna_baru" id="warna_baru" placeholder="Masukkan nama warna baru" class="form-control flex-grow-1">
                            <button type="button" id="btn_tambah_warna" class="btn btn-success btn-sm ml-2"><i class="fa fa-plus px-1"></i></button>
                        </div>
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama Warna</th>
                            <th scope="col" style="text-align: center; vertical-align: middle;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="body-warna">
                            @foreach ($data as $w => $warna)
                            <tr>
                                <td>{{ ++$w }}</td>
                                <td>{{ $warna->nama_warna }}</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <a href="/deletewarna/{{ $warna->id }}"><button class="btn btn-danger btn-sm"><i class="fa fa-trash p-1" aria-hidden="true"></i></button></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
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
<!-- ajax insert lokasi baru -->
<script>
    $('#btn_tambah_warna').on('click', function() {
        var warna_baru = $('#warna_baru').val();
        if(warna_baru!=""){
            $.ajax({
                url: "/addwarna",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    nama_warna:warna_baru
                },
                cache: false,
                success: function(dataResult){
                    console.log(dataResult);
                    var div = $(this).parent();
                    var r = 0;
                    if(dataResult.statusCode==200){
                        alert("Warna berhasil ditambahkan");
                        $('#warna_baru').val('');
                        $('#body-warna').empty();
                        for (var i=0;i<dataResult.warna.length;i++){
                            r++;
                            $('#body-warna').append('<tr><td>'+ r +'</td><td>' + dataResult.warna[i].nama_warna + '</td><td style="text-align: center; vertical-align: middle;"><a href="/deletewarna/'+dataResult.warna[i].id+'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash p-1" aria-hidden="true"></i></button></a></td></tr>');
                        }
                    }
                },
                error:function(error){
                    alert("Warna tidak berhasil ditambahkan");
                }
            });
        }
        else{
            alert('Harap input nama warna');
        }
    });
</script>
@endsection
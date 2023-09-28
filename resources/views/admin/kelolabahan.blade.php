@extends('layouts/navadmin')
@section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="col-lg-4 mt-3 mb-3 d-flex p-1">
                            <input type="text" name="bahan_baru" id="bahan_baru" placeholder="Masukkan nama bahan baru" class="form-control flex-grow-1">
                            <button type="button" id="btn_tambah_bahan" class="btn btn-success btn-sm ml-2"><i class="fa fa-plus px-1"></i></button>
                        </div>
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama Bahan</th>
                            <th scope="col" style="text-align: center; vertical-align: middle;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="body-bahan">
                            @foreach ($data as $b => $bahan)
                            <tr>
                                <td>{{ ++$b }}</td>
                                <td>{{ $bahan->nama_bahan }}</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <a href="/deletebahan/{{ $bahan->id }}"><button class="btn btn-danger btn-sm"><i class="fa fa-trash p-1" aria-hidden="true"></i></button></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<!-- ajax insert lokasi baru -->
<script>
    $('#btn_tambah_bahan').on('click', function() {
        var bahan_baru = $('#bahan_baru').val();
        if(bahan_baru!=""){
            $.ajax({
                url: "/addbahan",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    nama_bahan:bahan_baru
                },
                cache: false,
                success: function(dataResult){
                    console.log(dataResult);
                    var div = $(this).parent();
                    var r = 0;
                    if(dataResult.statusCode==200){
                        alert("Bahan berhasil ditambahkan");
                        $('#bahan_baru').val('');
                        $('#body-bahan').empty();
                        for (var i=0;i<dataResult.bahan.length;i++){
                            r++;
                            $('#body-bahan').append('<tr><td>'+ r +'</td><td>' + dataResult.bahan[i].nama_bahan + '</td><td style="text-align: center; vertical-align: middle;"><a href="/deletebahan/'+dataResult.bahan[i].id+'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash p-1" aria-hidden="true"></i></button></a></td></tr>');
                        }
                    }
                },
                error:function(error){
                    alert("Bahan tidak berhasil ditambahkan");
                }
            });
        }
        else{
            alert('Harap input nama bahan');
        }
    });
</script>
@endsection
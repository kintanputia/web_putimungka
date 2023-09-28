<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KatalogController extends Controller
{
    public function index(){
        $data = DB::table('produks')->orderby('id', 'desc')->get();
        return view('admin.katalogadmin', ['data'=>$data]);
    }
    public function index_propel(){
        $data = DB::table('produks')->orderby('id', 'desc')->get();
        return view('pelanggan.katalogproduk', ['data'=>$data]);
    }
    public function index_warna(){
        $warna = DB::table('warnas')->orderby('id', 'asc')->get();
        return view('admin.kelolawarna', ['data'=>$warna]);
    }
    public function insert_warna(Request $request)
    {
        $request->validate([
            'nama_warna' => 'required'
        ]);
        $add = DB::table('warnas')->insert([
            'nama_warna'=>$request->nama_warna
            ]);
        $update  = DB::table('warnas')->orderby('id', 'asc')->get();
        if ($add){
            return response()->json([
                'statusCode'=>200, 'warna'=>$update
            ]);
        }else {
            return response()->json([
                'statusCode'=>201
            ]);
        }
    }
    public function index_bahan(){
        $bahan = DB::table('bahans')->orderby('id', 'asc')->get();
        return view('admin.kelolabahan', ['data'=>$bahan]);
    }
    public function insert_bahan(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required'
        ]);
        $add = DB::table('bahans')->insert([
            'nama_bahan'=>$request->nama_bahan
            ]);
        $update  = DB::table('bahans')->orderby('id', 'asc')->get();
        if ($add){
            return response()->json([
                'statusCode'=>200, 'bahan'=>$update
            ]);
        }else {
            return response()->json([
                'statusCode'=>201
            ]);
        }
    }
    public function detaildproduk($id){
        $informasi = DB::table('produks')->where('id', $id)->first();
        $warna = DB::table('warna_produks')
                        ->where('id_produk', $id)
                        ->join('warnas', 'warna_produks.id_warna', '=', 'warnas.id')
                        ->get();
        $bahan = DB::table('bahan_produks')
                        ->where('id_produk', $id)
                        ->join('bahans', 'bahan_produks.id_bahan', '=', 'bahans.id')
                        ->get();
        return view('admin.detailprodukadmin', ['informasi'=>$informasi, 'warna'=>$warna, 'bahan'=>$bahan]);
    }
    public function detailprodukpel($id){
        $id_user = Auth::user()->id;
        $informasi = DB::table('produks')->where('id', $id)->first();
        $pelanggan = DB::table('pelanggans')->where('id_user', $id_user)->first();
        $warna = DB::table('warna_produks')
                        ->where('id_produk', $id)
                        ->join('warnas', 'warna_produks.id_warna', '=', 'warnas.id')
                        ->get();
        $bahan = DB::table('bahan_produks')
                        ->where('id_produk', $id)
                        ->join('bahans', 'bahan_produks.id_bahan', '=', 'bahans.id')
                        ->get();
        return view('pelanggan.detailproduk', ['informasi'=>$informasi, 'warna'=>$warna, 'bahan'=>$bahan, 'pelanggan'=>$pelanggan]);
    }
    public function create(){
        $warna = DB::table('warnas')->get();
        $bahan = DB::table('bahans')->get();
        return view('admin.tambahproduk', ['warna'=>$warna, 'bahan'=>$bahan]);
    }
    public function addproduct(Request $request){
        $validatedData = $request->validate([
            'nama_produk'=>'required',
            'harga'=>'required',
            'ukuran'=>'required',
            'berat'=>'required',
            'nama_motif'=>'required',
            'jenis_jahitan'=>'required',
            'model'=>'required',
            'waktu_pengerjaan'=>'nullable',
            'deskripsi'=>'required',
            'foto'=>'required|max:10240',
            'foto.*' => 'image',
            'selectedColors'=> 'required',
            'selectedMaterial'=> 'required'
        ],
        [
            'foto.max' => 'Ukuran foto melebihi 10 Mb'
        ]);

        $pics = [];
        if($request->hasfile('foto'))
         {
            foreach($request->file('foto') as $key => $fotos)
            {
                $destinationPath = 'fotoproduk/';
                $foto_name = date('YmdHis').'_'. $key . '.' . $fotos->getClientOriginalExtension();
                $fotos->move($destinationPath, $foto_name);
                $pics[] = $foto_name;
            }
         }
        // fungsi simpan
        $nama_foto = json_encode($pics); //array->string
        $warnaArray = explode(',', $validatedData['selectedColors']);
        $bahanArray = explode(',', $validatedData['selectedMaterial']);
        $simpan =  DB::table('produks')->insertGetId([
            'nama_produk'=>$validatedData['nama_produk'],
            'harga'=>$validatedData['harga'],
            'ukuran'=>$validatedData['ukuran'],
            'berat'=>$validatedData['berat'],
            'nama_motif'=>$validatedData['nama_motif'],
            'jenis_jahitan'=>$validatedData['jenis_jahitan'],
            'model'=>$validatedData['model'],
            'waktu_pengerjaan'=>$validatedData['waktu_pengerjaan'],
            'deskripsi'=>$validatedData['deskripsi'],
            'foto'=>$nama_foto
            ]);
        if ($simpan){
            foreach ($warnaArray as $warna) {
                DB::table('warna_produks')->insert([
                    'id_produk'=>$simpan,
                    'id_warna'=>$warna
                    ]);
            }
            foreach ($bahanArray as $bahan) {
                DB::table('bahan_produks')->insert([
                    'id_produk'=>$simpan,
                    'id_bahan'=>$bahan
                    ]);
            }
            return redirect()->action([KatalogController::class, 'index']);
        }
        else {
            return redirect()->back();
        }
    }
    public function destroy_warna($id)
    {
        $hapus = DB::table('warnas')->where('id', $id)
                            ->delete();
        if($hapus){
            return redirect()->action([KatalogController::class, 'index_warna']);
        }
        else{
            return redirect()->back();
        }
    }
    public function destroy_bahan($id)
    {
        $hapus = DB::table('bahans')->where('id', $id)
                            ->delete();
        if($hapus){
            return redirect()->action([KatalogController::class, 'index_bahan']);
        }
        else{
            return redirect()->back();
        }
    }
    public function destroy_produk($id)
    {
        $hapus = DB::table('produks')->where('id', $id)
                            ->delete();
        if($hapus){
            return redirect()->action([KatalogController::class, 'index']);
        }
        else{
            return redirect()->back();
        }
    }
    public function edit_produk($id)
    {
        $detail = DB::table('produks')->orderBy('produks.id', 'asc')->where('produks.id', $id)->first();
        $bahan = DB::table('bahan_produks')->join('bahans', 'bahan_produks.id_bahan', '=', 'bahans.id')->where('bahan_produks.id_produk', $id)->get();
        $warna = DB::table('warna_produks')->join('warnas', 'warna_produks.id_warna', '=', 'warnas.id')->where('warna_produks.id_produk', $id)->get();
        $allColors = DB::table('warnas')->get();
        $allMaterials = DB::table('bahans')->get();
        return view ('admin.editproduk', ['detail'=>$detail, 'warna'=>$warna, 'bahan'=>$bahan, 'allColors'=>$allColors, 'allMaterials'=>$allMaterials]);
    }
    public function editproduk_process(Request $request)
    {
        $validatedData = $request->validate([
            'nama_produk'=>'required',
            'harga'=>'required',
            'ukuran'=>'required',
            'berat'=>'required',
            'nama_motif'=>'required',
            'jenis_jahitan'=>'required',
            'model'=>'required',
            'waktu_pengerjaan'=>'nullable',
            'deskripsi'=>'required',
            'foto'=>'max:10240',
            'foto.*' => 'image',
            'selectedColors'=> 'required',
            'selectedMaterial'=> 'required'
        ],
        [
            'foto.max' => 'Ukuran foto melebihi 10 Mb'
        ]);
        $id = $request->id;
        $nama_produk = $validatedData['nama_produk'];
        $harga = $validatedData['harga'];
        $ukuran = $validatedData['ukuran'];
        $berat = $validatedData['berat'];
        $nama_motif = $validatedData['nama_motif'];
        $jenis_jahitan = $validatedData['jenis_jahitan'];
        $model = $validatedData['model'];
        $waktu_pengerjaan = $validatedData['waktu_pengerjaan'];
        $deskripsi = $validatedData['deskripsi'];
        $edit1 = false;
        $edit2 = false;
        if($request->hasfile('foto')){
            foreach($request->file('foto') as $key => $fotos)
            {
                $destinationPath = 'fotoproduk/';
                $foto_name = date('YmdHis').'_'. $key . '.' . $fotos->getClientOriginalExtension();
                $fotos->move($destinationPath, $foto_name);
                $pics[] = $foto_name;
            }
            $nama_foto = json_encode($pics); //array->string
            $edit1 = DB::table('produks')->where('id', $id)
                ->update([
                    'nama_produk'=>$nama_produk, 
                    'harga'=>$harga, 
                    'ukuran'=>$ukuran, 
                    'berat'=>$berat, 
                    'nama_motif'=>$nama_motif, 
                    'jenis_jahitan'=>$jenis_jahitan, 
                    'model'=>$model,  
                    'waktu_pengerjaan'=>$waktu_pengerjaan, 
                    'deskripsi'=>$deskripsi,
                    'foto'=>$nama_foto 
                ]);
        }else{
            $edit2 = DB::table('produks')->where('id', $id)
                ->update([
                    'nama_produk'=>$nama_produk, 
                    'harga'=>$harga, 
                    'ukuran'=>$ukuran, 
                    'berat'=>$berat, 
                    'nama_motif'=>$nama_motif, 
                    'jenis_jahitan'=>$jenis_jahitan, 
                    'model'=>$model,  
                    'waktu_pengerjaan'=>$waktu_pengerjaan, 
                    'deskripsi'=>$deskripsi
                ]);
        }
        $warArr = explode(',', $validatedData['selectedColors']);
        $bahArr = explode(',', $validatedData['selectedMaterial']);
        $warnaArray = array_unique($warArr);
        $bahanArray = array_unique($bahArr);
        
            DB::table('warna_produks')->where('id_produk', $id)->delete();
            DB::table('bahan_produks')->where('id_produk', $id)->delete();
            foreach ($warnaArray as $warna) {
                DB::table('warna_produks')->insert([
                    'id_produk'=>$id,
                    'id_warna'=>$warna
                    ]);
            }
            foreach ($bahanArray as $bahan) {
                DB::table('bahan_produks')->insert([
                    'id_produk'=>$id,
                    'id_bahan'=>$bahan
                    ]);
            }
            return redirect()->action([KatalogController::class, 'index']);
        
    }
}

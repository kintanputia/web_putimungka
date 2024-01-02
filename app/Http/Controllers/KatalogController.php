<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class KatalogController extends Controller
{   
    public function index_tl(){
        $produk = DB::table('produks')
            ->orderBy('id_produk', 'desc')
            ->get();

        // set price range
        $produk->transform(function ($product) {
            $hargaArray = DB::table('warna_bahan_produks')
                ->where('id_produk', $product->id_produk)
                ->pluck('harga')
                ->toArray();

                if (count($hargaArray) > 1) {
                    $minPrice = min($hargaArray);
                    $maxPrice = max($hargaArray);
                    $product->price_range = "Rp. " . number_format($minPrice, 0, ',', '.') . " - Rp. " . number_format($maxPrice, 0, ',', '.');
                } elseif (count($hargaArray) === 1) {
                    $product->price_range = "Rp. " . number_format($hargaArray[0], 0, ',', '.');
                } else {
                    $product->price_range = "Data Harga tidak ditemukan";
                }
                
                return $product;
        });

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;

        $currentPageItems = $produk->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $produkPaginated = new LengthAwarePaginator(
            $currentPageItems,
            count($produk),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
        return view('katalogtl', ['produk'=>$produkPaginated]);
    }
    public function index(){
        $produk = DB::table('produks')
            ->orderBy('id_produk', 'desc')
            ->get();

        // set price range
        $produk->transform(function ($product) {
            $hargaArray = DB::table('warna_bahan_produks')
                ->where('id_produk', $product->id_produk)
                ->pluck('harga')
                ->toArray();

                if (count($hargaArray) > 1) {
                    $minPrice = min($hargaArray);
                    $maxPrice = max($hargaArray);
                    $product->price_range = "Rp. " . number_format($minPrice, 0, ',', '.') . " - Rp. " . number_format($maxPrice, 0, ',', '.');
                } elseif (count($hargaArray) === 1) {
                    $product->price_range = "Rp. " . number_format($hargaArray[0], 0, ',', '.');
                } else {
                    $product->price_range = "Data Harga tidak ditemukan";
                }
                
                return $product;
        });

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;

        $currentPageItems = $produk->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $produkPaginated = new LengthAwarePaginator(
            $currentPageItems,
            count($produk),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
        
        return view('admin.katalogadmin', ['produk'=>$produkPaginated]);
    }
    public function index_propel(){
        $produk = DB::table('produks')
            ->orderBy('id_produk', 'desc')
            ->get();

        // set price range
        $produk->transform(function ($product) {
            $hargaArray = DB::table('warna_bahan_produks')
                ->where('id_produk', $product->id_produk)
                ->pluck('harga')
                ->toArray();

                if (count($hargaArray) > 1) {
                    $minPrice = min($hargaArray);
                    $maxPrice = max($hargaArray);
                    $product->price_range = "Rp. " . number_format($minPrice, 0, ',', '.') . " - Rp. " . number_format($maxPrice, 0, ',', '.');
                } elseif (count($hargaArray) === 1) {
                    $product->price_range = "Rp. " . number_format($hargaArray[0], 0, ',', '.');
                } else {
                    $product->price_range = "Data Harga tidak ditemukan";
                }
                
                return $product;
        });

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;

        $currentPageItems = $produk->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $produkPaginated = new LengthAwarePaginator(
            $currentPageItems,
            count($produk),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
        return view('pelanggan.katalogproduk', ['produk'=>$produkPaginated]);
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
        $nama_warna = ucwords($request->nama_warna);
        $add = DB::table('warnas')->insert([
            'nama_warna'=>$nama_warna
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
        $nama_bahan = ucwords($request->nama_bahan);
        $add = DB::table('bahans')->insert([
            'nama_bahan'=>$nama_bahan
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
    public function detail_produk_tl($id){
        $informasi = DB::table('produks')->where('id_produk', $id)->first();
        $hargaArray = DB::table('warna_bahan_produks')
            ->where('id_produk', $informasi->id_produk)
            ->pluck('harga')
            ->toArray();

        if (count($hargaArray) > 1) {
            $minPrice = min($hargaArray);
            $maxPrice = max($hargaArray);
            $informasi->price_range = "Rp. " . number_format($minPrice, 0, ',', '.') . " - Rp. " . number_format($maxPrice, 0, ',', '.');
        } elseif (count($hargaArray) === 1) {
            $informasi->price_range = "Rp. " . number_format($hargaArray[0], 0, ',', '.');
        } else {
            $informasi->price_range = "Data Harga tidak ditemukan";
        }
        
        $warna = DB::table('warna_bahan_produks')
                        ->where('id_produk', $id)
                        ->join('warnas', 'warna_bahan_produks.id_warna', '=', 'warnas.id')
                        ->select('id_warna', 'nama_warna')
                        ->distinct()
                        ->get();
        $bahan = DB::table('warna_bahan_produks')
                        ->where('id_produk', $id)
                        ->join('bahans', 'warna_bahan_produks.id_bahan', '=', 'bahans.id')
                        ->select('id_bahan', 'nama_bahan', 'harga')
                        ->distinct()
                        ->get();
        return view('detailproduktl', ['informasi'=>$informasi, 'warna'=>$warna, 'bahan'=>$bahan]);
    }
    public function detaildproduk($id){
        $informasi = DB::table('produks')->where('id_produk', $id)->first();
        $hargaArray = DB::table('warna_bahan_produks')
            ->where('id_produk', $informasi->id_produk)
            ->pluck('harga')
            ->toArray();

        if (count($hargaArray) > 1) {
            $minPrice = min($hargaArray);
            $maxPrice = max($hargaArray);
            $informasi->price_range = "Rp. " . number_format($minPrice, 0, ',', '.') . " - Rp. " . number_format($maxPrice, 0, ',', '.');
        } elseif (count($hargaArray) === 1) {
            $informasi->price_range = "Rp. " . number_format($hargaArray[0], 0, ',', '.');
        } else {
            $informasi->price_range = "Data Harga tidak ditemukan";
        }

        $warna = DB::table('warna_bahan_produks')
                        ->where('id_produk', $id)
                        ->join('warnas', 'warna_bahan_produks.id_warna', '=', 'warnas.id')
                        ->select('id_warna', 'nama_warna')
                        ->distinct()
                        ->get();
        $bahan = DB::table('warna_bahan_produks')
                        ->where('id_produk', $id)
                        ->join('bahans', 'warna_bahan_produks.id_bahan', '=', 'bahans.id')
                        ->select('id_bahan', 'nama_bahan', 'harga')
                        ->distinct()
                        ->get();
        return view('admin.detailprodukadmin', ['informasi'=>$informasi, 'warna'=>$warna, 'bahan'=>$bahan]);
    }
    public function detailprodukpel($id){
        $id_user = Auth::user()->id;
        $informasi = DB::table('produks')->where('id_produk', $id)->first();
        $hargaArray = DB::table('warna_bahan_produks')
            ->where('id_produk', $informasi->id_produk)
            ->pluck('harga')
            ->toArray();

        if (count($hargaArray) > 1) {
            $minPrice = min($hargaArray);
            $maxPrice = max($hargaArray);
            $informasi->price_range = "Rp. " . number_format($minPrice, 0, ',', '.') . " - Rp. " . number_format($maxPrice, 0, ',', '.');
        } elseif (count($hargaArray) === 1) {
            $informasi->price_range = "Rp. " . number_format($hargaArray[0], 0, ',', '.');
        } else {
            $informasi->price_range = "Data Harga tidak ditemukan";
        }

        $pelanggan = DB::table('pelanggans')->where('id_user', $id_user)->first();
        $warna = DB::table('warna_bahan_produks')
                        ->where('id_produk', $id)
                        ->join('warnas', 'warna_bahan_produks.id_warna', '=', 'warnas.id')
                        ->select('id_warna', 'nama_warna')
                        ->distinct()
                        ->get();
        $bahan = DB::table('warna_bahan_produks')
                        ->where('id_produk', $id)
                        ->join('bahans', 'warna_bahan_produks.id_bahan', '=', 'bahans.id')
                        ->select('id_bahan', 'nama_bahan', 'harga')
                        ->distinct()
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
            'ukuran'=>'required',
            'berat'=>'required|numeric|min:1',
            'nama_motif'=>'required',
            'jenis_jahitan'=>'required',
            'model'=>'required',
            'waktu_pengerjaan'=>'required|numeric|min:1',
            'deskripsi'=>'required',
            'foto'=>'required|max:10240',
            'foto.*' => 'image',
            'selectedColors'=> 'required',
            'selectedMaterial'=> 'required'
        ],
        [
            'foto.max' => 'Ukuran foto melebihi 10 Mb',
            'waktu_pengerjaan.min' => 'Waktu pengerjaan harus lebih besar dari 0',
            'berat.min' => 'Waktu pengerjaan harus lebih besar dari 0'
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
        $bahanJson = $validatedData['selectedMaterial'];
        $bahanArray = json_decode($bahanJson, true); //obj -> array
        if ($bahanArray === null && json_last_error() !== JSON_ERROR_NONE) {
            $jsonError = json_last_error_msg();
            var_dump("JSON decoding error: $jsonError", $bahanJson);
        }
        $warnaArray = array_values(array_unique($warnaArray));
        $simpan =  DB::table('produks')->insertGetId([
            'nama_produk'=>$validatedData['nama_produk'],
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
            foreach ($bahanArray as $bahan) {
                $harga = $bahan['harga_bahan'];
                foreach ($warnaArray as $warna) {
                    DB::table('warna_bahan_produks')->insert([
                        'id_produk'=>$simpan,
                        'id_bahan'=>$bahan['id'],
                        'id_warna'=>$warna,
                        'harga'=>$harga
                    ]);
                }
            }
            return redirect()->action([KatalogController::class, 'index']);
        }
        else {
            return redirect()->back();
        }
    }
    public function destroy_warna($id)
    {
        try {
            $hapus = DB::table('warnas')->where('id', $id)
                                ->delete();
            if($hapus){
                return redirect()->action([KatalogController::class, 'index_warna']);
            }
            else{
                return redirect()->back();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Warna tidak dapat dihapus karena terdapat produk yang menggunakan warna ini');
        }
    }
    public function destroy_bahan($id)
    {
        try {
            $hapus = DB::table('bahans')->where('id', $id)
                                ->delete();
            if($hapus){
                return redirect()->action([KatalogController::class, 'index_bahan']);
            }
            else{
                return redirect()->back();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bahan tidak dapat dihapus karena terdapat produk yang menggunakan bahan ini');
        }
    }
    public function destroy_produk($id)
    {
        try {
            $hapus = DB::table('produks')->where('id_produk', $id)->delete();
    
            if ($hapus) {
                return redirect()->action([KatalogController::class, 'index']);
            } else {
                return redirect()->back()->with('error', 'Produk Gagal Dihapus');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Produk tidak dapat dihapus karena produk sudah terdapat dalam data transaksi');
        }
    }
    public function edit_produk($id)
    {
        $detail = DB::table('produks')->orderBy('produks.id_produk', 'asc')->where('produks.id_produk', $id)->first();
        $bahan = DB::table('warna_bahan_produks')->join('bahans', 'warna_bahan_produks.id_bahan', '=', 'bahans.id')->select('id_bahan', 'nama_bahan', 'harga')->where('warna_bahan_produks.id_produk', $id)->distinct()->get();
        $warna = DB::table('warna_bahan_produks')->join('warnas', 'warna_bahan_produks.id_warna', '=', 'warnas.id')->select('id_warna', 'nama_warna')->where('warna_bahan_produks.id_produk', $id)->distinct()->get();
        $allColors = DB::table('warnas')->get();
        $allMaterials = DB::table('bahans')->get();
        return view ('admin.editproduk', ['detail'=>$detail, 'warna'=>$warna, 'bahan'=>$bahan, 'allColors'=>$allColors, 'allMaterials'=>$allMaterials]);
    }
    public function editproduk_process(Request $request)
    {
        $validatedData = $request->validate([
            'nama_produk'=>'required',
            'ukuran'=>'required',
            'berat'=>'required|numeric|min:1',
            'nama_motif'=>'required',
            'jenis_jahitan'=>'required',
            'model'=>'required',
            'waktu_pengerjaan'=>'required|numeric|min:1',
            'deskripsi'=>'required',
            'foto'=>'max:10240',
            'foto.*' => 'image',
            'selectedColors'=> 'required',
            'selectedMaterial'=> 'required',
            'removedColors'=>'nullable',
            'removedMaterials'=>'nullable'
        ],
        [
            'foto.max' => 'Ukuran foto melebihi 10 Mb',
            'waktu_pengerjaan.min' => 'Waktu pengerjaan harus lebih besar dari 0',
            'berat.min' => 'Waktu pengerjaan harus lebih besar dari 0'
        ]);
        $id = $request->id;
        $nama_produk = $validatedData['nama_produk'];
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
            $edit1 = DB::table('produks')->where('id_produk', $id)
                ->update([
                    'nama_produk'=>$nama_produk, 
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
            $edit2 = DB::table('produks')->where('id_produk', $id)
                ->update([
                    'nama_produk'=>$nama_produk, 
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
        $warnaArray = array_unique($warArr);
        $bahanJson = $validatedData['selectedMaterial'];
        $bahanArray = json_decode($bahanJson, true); //obj -> array
        if ($bahanArray === null && json_last_error() !== JSON_ERROR_NONE) {
            $jsonError = json_last_error_msg();
            var_dump("JSON decoding error: $jsonError", $bahanJson);
        }
        $HwarArr = explode(',', $validatedData['removedColors']);
        $HbahArr = explode(',', $validatedData['removedMaterials']);
        $HwarnaArray = array_unique($HwarArr);
        $HbahanArray = array_unique($HbahArr);

        DB::table('warna_bahan_produks')->where('id_produk', $id)->whereIn('id_warna', $HwarnaArray)->delete();
        DB::table('warna_bahan_produks')->where('id_produk', $id)->whereIn('id_bahan', $HbahanArray)->delete();

            foreach ($bahanArray as $bahan) {
                $harga = $bahan['harga_bahan'];
                
                foreach ($warnaArray as $warna) {
                    // cek record
                    $existingRecord = DB::table('warna_bahan_produks')
                        ->where('id_produk', $id)
                        ->where('id_bahan', $bahan['id'])
                        ->where('id_warna', $warna)
                        ->first();
            
                    if ($existingRecord) {
                        // jika ada update harga saja
                        DB::table('warna_bahan_produks')
                            ->where('id_produk', $id)
                            ->where('id_bahan', $bahan['id'])
                            ->where('id_warna', $warna)
                            ->update(['harga' => $harga]);
                    } else {
                        // insert data bary
                        DB::table('warna_bahan_produks')->insert([
                            'id_produk' => $id,
                            'id_bahan' => $bahan['id'],
                            'id_warna' => $warna,
                            'harga' => $harga
                        ]);
                    }
                }
            }            
            return redirect()->action([KatalogController::class, 'index']);
        
    }
}

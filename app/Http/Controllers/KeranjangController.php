<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function cart_quantity (){
        $id_user = Auth::user()->id;
        $cartQ = DB::table('keranjangs')->where('id_user', $id_user)->sum('jumlah');
        return response()->json(['cartQuantity' => $cartQ]);
    }
    public function insert_keranjang(Request $request){
        $request->validate([
            'warna'=> 'required',
            'bahan'=> 'required',
            'jumlah'=>'required',
            'harga_produk'=> 'required'
        ]);
        $id_user = $request->id_user;
        $id_produk = $request->id_produk;
        $add = DB::table('keranjangs')->insert([
            'id_user'=>$id_user,
            'id_produk'=>$id_produk,
            'id_warna'=>$request->warna,
            'id_bahan'=>$request->bahan,
            'jumlah'=>$request->jumlah,
            'harga_produk'=>$request->harga_produk,
            'tambahan_motif'=>$request->tambahan_motif
            ]);
    }
    public function isi_keranjang_admin($id){
        $data1 = DB::table('keranjangs')->where('id_user', $id)
                    ->join('produks', 'produks.id_produk', '=', 'keranjangs.id_produk')
                    ->join('bahans', 'bahans.id', '=', 'keranjangs.id_bahan')
                    ->join('warnas', 'warnas.id', '=', 'keranjangs.id_warna')
                    ->get();
        $provinsi = \Indonesia::allProvinces()->sortBy('name')->pluck('name', 'id');
        $route_get_kota = route('get.kota');
        $route_get_kecamatan = route('get.kecamatan');
        $data = json_decode($data1);

        $uniqueElements = [];

        foreach ($data as $item) {
            $key = $item->id_produk;
            $combination = $item->nama_warna . '-' . $item->nama_bahan;

            if (!isset($uniqueElements[$key])) {
                $uniqueElements[$key] = [];
            }

            $found = false;
            foreach ($uniqueElements[$key] as &$uniqueItem) {
                if ($uniqueItem->id_warna === $item->id_warna && $uniqueItem->id_bahan === $item->id_bahan && $uniqueItem->tambahan_motif === $item->tambahan_motif) {
                    $uniqueItem->jumlah += $item->jumlah;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $uniqueElements[$key][] = $item;
            }
        }

        $result = [];

        foreach ($uniqueElements as $key => $items) {
            foreach ($items as $item) {
                $result[] = $item;
            }
        }
        return view('admin.keranjangadmin', ['uniqueV'=>$result, 'provinsi'=>$provinsi]);
    }
    public function isi_keranjang($id){
        $pelanggan = DB::table('pelanggans')->where('id_user', $id)
                ->leftJoin('alamat_pelanggans', 'alamat_pelanggans.id_pelanggan', '=', 'pelanggans.id_pelanggan')
                ->leftJoin('indonesia_districts', 'indonesia_districts.id', '=', 'alamat_pelanggans.id_kecamatan')
                ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'indonesia_districts.city_code')
                ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'indonesia_cities.province_code')
                ->select('pelanggans.*',
                         'alamat_pelanggans.*', 
                        'indonesia_districts.id as id_kecamatan',
                        'indonesia_districts.name as nama_kecamatan',
                        'indonesia_cities.id as id_kota',
                        'indonesia_cities.name as nama_kota',
                        'indonesia_provinces.id as id_provinsi',
                        'indonesia_provinces.name as nama_provinsi'
                        )
                ->first();
        $data1 = DB::table('keranjangs')->where('id_user', $id)
                    ->join('produks', 'produks.id_produk', '=', 'keranjangs.id_produk')
                    ->join('bahans', 'bahans.id', '=', 'keranjangs.id_bahan')
                    ->join('warnas', 'warnas.id', '=', 'keranjangs.id_warna')
                    ->get();
        $provinsi = \Indonesia::allProvinces()->sortBy('name')->pluck('name', 'id');
        $route_get_kota = route('get.kota');
        $route_get_kecamatan = route('get.kecamatan');
        $data = json_decode($data1);

        $uniqueElements = [];

        foreach ($data as $item) {
            $key = $item->id_produk;
            $combination = $item->id_warna . '-' . $item->id_bahan;

            if (!isset($uniqueElements[$key])) {
                $uniqueElements[$key] = [];
            }

            $found = false;
            foreach ($uniqueElements[$key] as &$uniqueItem) {
                if ($uniqueItem->id_warna === $item->id_warna && $uniqueItem->id_bahan === $item->id_bahan && $uniqueItem->tambahan_motif === $item->tambahan_motif) {
                    $uniqueItem->jumlah += $item->jumlah;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $uniqueElements[$key][] = $item;
            }
        }

        $result = [];

        foreach ($uniqueElements as $key => $items) {
            foreach ($items as $item) {
                $result[] = $item;
            }
        }
        return view('pelanggan.keranjangpelanggan', ['uniqueV'=>$result, 'pelanggan'=>$pelanggan, 'provinsi'=>$provinsi]);
    }
    public function get_kota()
    {
        $province_id = request('province_id');
        $kota = \Indonesia::findProvince($province_id, ['cities'])->cities->sortBy('name')->pluck('name', 'id');
        return view('layouts.list_kota', compact('kota'));
    }
    public function get_kecamatan()
    {
        $city_id = request('city_id');
        $kecamatan = \Indonesia::findCity($city_id, ['districts'])->districts->sortBy('name')->pluck('name', 'id');

        return view('layouts.list_kecamatan', compact('kecamatan'));
    }
    public function destroy_keranjang(Request $request)
    {
        $id_produk = $request->input('id_produk');
        $id_warna = $request->input('id_warna');
        $id_bahan = $request->input('id_bahan');
        $harga_produk = $request->input('harga_produk');

        DB::table('keranjangs')
            ->where('id_produk', $id_produk)
            ->where('id_warna', $id_warna)
            ->where('id_bahan', $id_bahan)
            ->where('harga_produk', $harga_produk)
            ->delete();
            
        return redirect()->back();
    }
    public function checkOngkir(Request $request){
        $kota = DB::table('city_ongkirs')
                ->where('name', '=', $request->destination)
                ->value('city_id');
        $asal = DB::table('city_ongkirs')
                ->where('name', '=', $request->origin)
                ->value('city_id');
        if ($kota && $asal) {
            $kotaString = $kota;
            $originString = $asal;
        }
        else {
            $kotaString = 1;
            $originString = 2;
        }
        try {
            $response = Http::withOptions(['verify' => false,])->withHeaders([
                'key' => env('RAJAONGKIR_API_KEY')
            ])->post('https://api.rajaongkir.com/starter/cost',[
                'origin'        => $originString,
                'destination'   => $kotaString,
                'weight'        => $request->weight,
                'courier'       => $request->courier
            ])
            ->json()['rajaongkir']['results'][0]['costs'];
            return response()->json($response);
                if (isset($response['rajaongkir']['results'][0]['costs'])) {
                    return response()->json($response['rajaongkir']['results'][0]['costs']);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid API response structure',
                        'data' => [],
                        'response' => $response
                    ]);
                }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'data'    => []
            ]);
        }
    }
    public function insert_alamat_baru(Request $request){
        $request->validate([
            'id_pelanggan'=> 'required',
            'kecamatan'=> 'required',
            'alamat'=>'required',
            'kode_pos'=> 'required'
        ]);
        $id_pelanggan = $request->id_pelanggan;
        $add = DB::table('alamat_pelanggans')->insertGetId([
            'id_pelanggan'=>$id_pelanggan,
            'alamat'=>$request->alamat,
            'id_kecamatan'=>$request->kecamatan,
            'kode_pos'=>$request->kode_pos
            ]);

        if ($add) {
            return response()->json(['message' => 'Alamat berhasil disimpan', 'id_alamat' => $add], 200);
        } else {
            return response()->json(['error' => 'Gagal menyimpan alamat'], 500);
        }
    }
}

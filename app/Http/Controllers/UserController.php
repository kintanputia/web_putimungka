<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){
        $data = DB::table('pelanggans')
        ->orderBy('pelanggans.id_pelanggan', 'desc')
        ->join('users', 'users.id', '=', 'pelanggans.id_user')
        ->join(DB::raw('(SELECT MIN(id_alamat) as id_alamat, id_pelanggan FROM alamat_pelanggans GROUP BY id_pelanggan) as first_alamat'), function($join) {
            $join->on('first_alamat.id_pelanggan', '=', 'pelanggans.id_pelanggan');
        })
        ->join('alamat_pelanggans', function($join) {
            $join->on('first_alamat.id_alamat', '=', 'alamat_pelanggans.id_alamat');
        })
        ->paginate(10);

        return view('admin.pengguna', ['data'=>$data]);
    }
    // halaman edit profil pelanggan admin
    public function edit_profil_pel_admin($id){
        $data = DB::table('pelanggans')->where('pelanggans.id_pelanggan', $id)
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
        // dd($data);
        $allProvinces = DB::table('indonesia_provinces')->get();
        $allCities = DB::table('indonesia_cities')
                            ->join('indonesia_provinces', 'indonesia_provinces.code', '=', 'indonesia_cities.province_code')
                            ->where('indonesia_provinces.id', $data->id_provinsi)
                            ->select('indonesia_cities.id as id', 'indonesia_cities.name as name')
                            ->get();
        $allKecamatan = DB::table('indonesia_districts')
                            ->join('indonesia_cities', 'indonesia_districts.city_code', '=', 'indonesia_cities.code')
                            ->where('indonesia_cities.id', $data->id_kota)
                            ->select('indonesia_districts.id as id', 'indonesia_districts.name as name')
                            ->get();

        return view('admin.editprofilpeladmin', compact('data', 'allProvinces', 'allCities', 'allKecamatan'));
    }
    public function editprofilpadm_process(Request $request){
        $validatedData = $request->validate([
            'nama_pelanggan'=>'required',
            'no_hp'=>'required',
            'alamat'=>'required',
            'province_id'=>'required',
            'city_id'=>'required',
            'kecamatan_id'=>'required',
            'kode_pos'=>'required'
        ]);
        $id = $request->id;
        DB::table('pelanggans')->where('id_pelanggan', $id)->update([
            'nama_pelanggan'=>$validatedData['nama_pelanggan'],
            'no_hp'=>$validatedData['no_hp']
            ]);
        DB::table('alamat_pelanggans')->where('id_pelanggan', $id)->limit(1)->update([
            'alamat'=>$validatedData['alamat'],
            'id_kecamatan'=>$validatedData['kecamatan_id'],
            'kode_pos'=>$validatedData['kode_pos']
            ]);
    }
    public function detail_profil_pelanggan ($id){
        $data = DB::table('pelanggans')->where('pelanggans.id_pelanggan', $id)
                        ->leftJoin('alamat_pelanggans', 'alamat_pelanggans.id_pelanggan', '=', 'pelanggans.id_pelanggan')
                        ->leftJoin('indonesia_districts', 'indonesia_districts.id', '=', 'alamat_pelanggans.id_kecamatan')
                        ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'indonesia_districts.city_code')
                        ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'indonesia_cities.province_code')
                        ->select('pelanggans.*',
                                'alamat_pelanggans.*', 
                                'indonesia_districts.name as nama_kecamatan',
                                'indonesia_cities.name as nama_kota',
                                'indonesia_provinces.name as nama_provinsi'
                                )
                        ->first();
        
        return view('admin.detailprofilpelanggan', compact('data'));
    }
    public function destroy_pelanggan($id)
    {
        
        try {
            $hapus = DB::table('pelanggans')->where('id_pelanggan', $id)
                                ->delete();
            if($hapus){
                return redirect()->action([UserController::class, 'index']);
            }
            else{
                return redirect()->back();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data pelanggan ini tidak dapat dihapus karena pelanggan ini sudah melakukan transaksi sebelumnya');
        }
    }
}

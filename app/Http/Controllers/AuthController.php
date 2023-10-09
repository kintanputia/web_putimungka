<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;


class AuthController extends Controller
{
    public function home(){
        return view('welcome');
    }
    // login view
    public function login(){
    return view('auth.login');
    }
    // login
    public function postLogin(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        $re = $request->only('email', 'password');
        $ingat = $request->rememberme ? true : false;
    
        if (Auth::attempt($re, $ingat)) {
            if (Auth()->user()->role == 'admin') {
                return redirect('/dashboardadmin');
            } else if (Auth()->user()->role == 'pelanggan') {
                return redirect('/dashboard');
            }
        } else {
            $user = User::where('email', $re['email'])->first();
            if (!$user) {
                // user tidak ada
                return redirect('/')
                    ->with('status', 'User dengan email tersebut tidak ditemukan')
                    ->withInput();
            } else {
                // password salah
                return redirect('/')
                    ->with('status', 'Email atau password Anda salah')
                    ->withInput();
            }
        }
    }
    public function profil_pelanggan ($id){
        $data = DB::table('pelanggans')->where('id_user', $id)->first();
        $nama_provinsi_str = '';
        $nama_kota_str = '';
        $nama_kecamatan_str = '';
        // get nama provinsi
        $id_provinsi_coll = DB::table('pelanggans')
            ->select('id_provinsi')
            ->where('id_user', $id)
            ->get();
        if ($id_provinsi_coll->isEmpty()) {
            $nama_provinsi = null;
        } 
        else {
            $id_provinsi = $id_provinsi_coll->pluck('id_provinsi')->first();
            $nama_provinsi = DB::table('indonesia_provinces')
                ->where('id', $id_provinsi)
                ->pluck('name');
            // convert array menjadi str
            if ($nama_provinsi !== null && !empty($nama_provinsi)) {
                $nama_provinsi_str = $nama_provinsi[0];
            } 
            else {
                $nama_provinsi_str = '';
            }
        }
        // get nama kota/kab
        $id_kota_coll = DB::table('pelanggans')
            ->select('id_kota')
            ->where('id_user', $id)
            ->get();

        if ($id_kota_coll->isEmpty()) {
            $nama_kota = null;
        } 
        else {
            $id_kota = $id_kota_coll->pluck('id_kota')->first();
            $nama_kota = DB::table('indonesia_cities')
                ->where('id', $id_kota)
                ->pluck('name');
            // convert array menjadi str
            if ($nama_kota !== null && !empty($nama_kota)) {
                $nama_kota_str = $nama_kota[0];
            } 
            else {
                $nama_kota_str = '';
            }
        }
        // get nama kecamatan
        $id_kecamatan_coll = DB::table('pelanggans')
            ->select('id_kecamatan')
            ->where('id_user', $id)
            ->get();

        if ($id_kecamatan_coll->isEmpty()) {
            $nama_kecamatan = null;
        } 
        else {
            $id_kecamatan = $id_kecamatan_coll->pluck('id_kecamatan')->first();
            $nama_kecamatan = DB::table('indonesia_districts')
                ->where('id', $id_kecamatan)
                ->pluck('name');
            // convert array menjadi str
            if ($nama_kecamatan !== null && !empty($nama_kecamatan)) {
                $nama_kecamatan_str = $nama_kecamatan[0];
            } 
            else {
                $nama_kecamatan_str = '';
            }
        }
        return view('pelanggan.profilpelanggan', ['data'=>$data, 'nama_provinsi'=>$nama_provinsi_str, 'nama_kota'=>$nama_kota_str, 'nama_kecamatan'=>$nama_kecamatan_str]);
    }
    public function tambah_profil_pelanggan(){
        $provinsi = \Indonesia::allProvinces()->sortBy('name')->pluck('name', 'id');
        $route_get_kota = route('get.kota');
        $route_get_kecamatan = route('get.kecamatan');
        return view('pelanggan.tambahprofilpelanggan', compact('provinsi'));
    }
    public function get_kota()
    {
        $province_id = request('province_id');
        $kota = \Indonesia::findProvince($province_id, ['cities'])->cities->sortBy('name')->pluck('name', 'id');
        return view('layouts.list_kota', compact('kota'));
    }
    public function get_kota_edit()
    {
        $province_id = request('province_id');
        $kota = \Indonesia::findProvince($province_id, ['cities'])->cities->sortBy('name')->pluck('name', 'id');
        return response()->json($kota);
    }
    public function get_kecamatan()
    {
        $city_id = request('city_id');
        $kecamatan = \Indonesia::findCity($city_id, ['districts'])->districts->sortBy('name')->pluck('name', 'id');

        return view('layouts.list_kecamatan', compact('kecamatan'));
    }
    public function get_kecamatan_edit()
    {
        $city_id = request('city_id');
        $kecamatan = \Indonesia::findCity($city_id, ['districts'])->districts->sortBy('name')->pluck('name', 'id');

        return response()->json($kecamatan);
    }
    public function add_profil(Request $request){
        $validatedData = $request->validate([
            'nama_pelanggan'=>'required',
            'no_hp'=>'required',
            'alamat'=>'required',
            'province_id'=>'required',
            'city_id'=>'required',
            'kecamatan_id'=>'required',
            'kode_pos'=>'required'
        ]);
        $id = $request->id_user;
        DB::table('pelanggans')->insert([
            'id_user'=>$id,
            'nama_pelanggan'=>$validatedData['nama_pelanggan'],
            'no_hp'=>$validatedData['no_hp'],
            'alamat'=>$validatedData['alamat'],
            'id_provinsi'=>$validatedData['province_id'],
            'id_kota'=>$validatedData['city_id'],
            'id_kecamatan'=>$validatedData['kecamatan_id'],
            'kode_pos'=>$validatedData['kode_pos']
            ]);
    }
    // halaman edit profil pelanggan
    public function edit_profil_pelanggan($id){
        $data = DB::table('pelanggans')->where('id_user', $id)->first();
        $allProvinces = DB::table('indonesia_provinces')->get();
        // get id provinsi
        $id_provinsi_coll = DB::table('pelanggans')
            ->select('id_provinsi')
            ->where('id_user', $id)
            ->get();
        if ($id_provinsi_coll->isEmpty()) {
            $nama_provinsi_obj = null;
        } 
        else {
            $id_provinsi = $id_provinsi_coll->pluck('id_provinsi')->first();
            $nama_provinsi_obj = DB::table('indonesia_provinces')
                                    ->where('id', $id_provinsi)
                                    ->first(); 
            if ($nama_provinsi_obj !== null) {
                $nama_provinsi_int = intval($nama_provinsi_obj->id);
            } else {
                $nama_provinsi_int = 0;
            }
        }
        // get id kota/kab
        $allCities = DB::table('indonesia_cities')
                            ->join('indonesia_provinces', 'indonesia_provinces.code', '=', 'indonesia_cities.province_code')
                            ->where('indonesia_provinces.id', $nama_provinsi_int)
                            ->select('indonesia_cities.id as id', 'indonesia_cities.name as name')
                            ->get();
        $id_kota_coll = DB::table('pelanggans')
            ->select('id_kota')
            ->where('id_user', $id)
            ->get();
        if ($id_kota_coll->isEmpty()) {
            $nama_kota_obj = null;
        } 
        else {
            $id_kota = $id_kota_coll->pluck('id_kota')->first();
            $nama_kota_obj = DB::table('indonesia_cities')
                                    ->where('id', $id_kota)
                                    ->first(); 
            if ($nama_kota_obj !== null) {
                $nama_kota_int = intval($nama_kota_obj->id);
            } else {
                $nama_kota_int = 0;
            }
        }
        // get id kecamatan
        $allKecamatan = DB::table('indonesia_districts')
                            ->join('indonesia_cities', 'indonesia_districts.city_code', '=', 'indonesia_cities.code')
                            ->where('indonesia_cities.id', $nama_kota_int)
                            ->select('indonesia_districts.id as id', 'indonesia_districts.name as name')
                            ->get();
        $id_kecamatan_coll = DB::table('pelanggans')
            ->select('id_kecamatan')
            ->where('id_user', $id)
            ->get();
        if ($id_kecamatan_coll->isEmpty()) {
            $nama_kecamatan_obj = null;
        } 
        else {
            $id_kecamatan = $id_kecamatan_coll->pluck('id_kecamatan')->first();
            $nama_kecamatan_obj = DB::table('indonesia_districts')
                                    ->where('id', $id_kecamatan)
                                    ->first(); 
            if ($nama_kecamatan_obj !== null) {
                $nama_kecamatan_int = intval($nama_kecamatan_obj->id);
            } else {
                $nama_kecamatan_int = 0;
            }
        }
        return view('pelanggan.editprofilpelanggan', ['data'=>$data, 'nama_provinsi'=>$nama_provinsi_int, 'allProvinces'=>$allProvinces, 'allCities'=>$allCities, 'nama_kota'=>$nama_kota_int, 'allKecamatan'=>$allKecamatan, 'nama_kecamatan'=>$nama_kecamatan_int]);
    }
    public function editprofilp_process(Request $request){
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
        DB::table('pelanggans')->where('id_user', $id)->update([
            'nama_pelanggan'=>$validatedData['nama_pelanggan'],
            'no_hp'=>$validatedData['no_hp'],
            'alamat'=>$validatedData['alamat'],
            'id_provinsi'=>$validatedData['province_id'],
            'id_kota'=>$validatedData['city_id'],
            'id_kecamatan'=>$validatedData['kecamatan_id'],
            'kode_pos'=>$validatedData['kode_pos']
            ]);
    }
    //register view
    public function register(){
        return view('auth.register');
    }
    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}

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
                return redirect('/login')
                    ->with('status', 'User dengan email tersebut tidak ditemukan')
                    ->withInput();
            } else {
                // password salah
                return redirect('/login')
                    ->with('status', 'Email atau password Anda salah')
                    ->withInput();
            }
        }
    }
    public function profil_pelanggan ($id){
        $data = DB::table('pelanggans')->where('id_user', $id)
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
        return view('pelanggan.profilpelanggan', compact('data'));
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
        $pel = DB::table('pelanggans')->insertGetId([
            'id_user'=>$id,
            'nama_pelanggan'=>$validatedData['nama_pelanggan'],
            'no_hp'=>$validatedData['no_hp']
            ]);
        if($pel){
            DB::table('alamat_pelanggans')->insert([
                'id_pelanggan'=>$pel,
                'alamat'=>$validatedData['alamat'],
                'id_kecamatan'=>$validatedData['kecamatan_id'],
                'kode_pos'=>$validatedData['kode_pos']
                ]);
        }
    }
    // halaman edit profil pelanggan
    public function edit_profil_pelanggan($id){
        $data = DB::table('pelanggans')->where('id_user', $id)
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
        return view('pelanggan.editprofilpelanggan', compact('data', 'allProvinces', 'allCities', 'allKecamatan'));
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
            'no_hp'=>$validatedData['no_hp']
            ]);
        $id_pelanggan = $request->id_pelanggan;
        DB::table('alamat_pelanggans')->where('id_pelanggan', $id_pelanggan)->limit(1)->update([
                'alamat'=>$validatedData['alamat'],
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

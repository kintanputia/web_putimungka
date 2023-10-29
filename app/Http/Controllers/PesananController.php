<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PesananController extends Controller
{
    public function index_pesanan()
    {
        $id_user = Auth::user()->id;
        $data = DB::table('transaksi_pelanggans')->orderby('transaksi_pelanggans.id_transaksi', 'desc')->where('id_user', $id_user)->paginate(10);
        return view('pelanggan.daftarpesananpelanggan', ['data'=>$data]);
    }
    public function index_pesananadm(Request $request)
    {
        $user = DB::table('users')->get();
        $status = $request->input('status');
        $query1 = DB::table('transaksi_admins as ta')
                        ->orderBy('ta.id_transaksi', 'desc');
        $query2 = DB::table('transaksi_pelanggans as tp')
                        ->leftJoin('pelanggans', 'pelanggans.id_user', '=', 'tp.id_user')
                        ->orderBy('tp.id_transaksi', 'desc');
        if ($status && $status !== 'all') {
            $query1->where('status_transaksi', $status);
            $query2->where('status_transaksi', $status);
        }
        $data_admin = $query1->paginate(10);
        $data_pelanggan = $query2->paginate(10);

        return view('admin.pesananadmin', compact('data_admin', 'data_pelanggan', 'status', 'user'));
    }
    public function insert_pesanan(Request $request)
    {
        $id_alamat = $request->id_alamat;
        $tglSkrg = Carbon::now();
        $formattedTglSkrg = $tglSkrg->format('d-m-Y');
        $tgl_pesan = date('Y-m-d', strtotime($formattedTglSkrg));
        $id_user = Auth::user()->id;
        $status_transaksi = 'Belum Dibayar';
        $total_belanja = intval($request->total_belanja);
        $biaya_ongkir = intval($request->biaya_ongkir);

        $alamat = DB::table('alamat_pelanggans')->where('id_alamat', $id_alamat)->first();

        $addtransaksi = DB::table('transaksi_pelanggans')->insertGetId([
            'id_user'=>$id_user,
            'kirim_ekspedisi'=>$request->kirim_ekspedisi,
            'tgl_pesan'=>$tgl_pesan,
            'total_belanja'=>$total_belanja,
            'status_transaksi'=>$status_transaksi,
            'note_transaksi'=>$request->note_transaksi
            ]);
        
        if ($request->kirim_ekspedisi == true){
            DB::table('distribusi_barang_pelanggans')->insertGetId([
                'id_transaksi'=>$addtransaksi,
                'id_alamat'=>$alamat->id_alamat,
                'biaya_ongkir'=>$biaya_ongkir,
                'layanan_ekspedisi'=>$request->layanan_ekspedisi,
            ]);
        }
        if ($addtransaksi) {
            // masukkan detail transaksi
            foreach ($request->dataArray as $detail) {
                // hapus isi keranjang
                DB::table('keranjangs')
                        ->where('id_produk', $detail['id_produk'])
                        ->where('id_warna', $detail['nama_warna'])
                        ->where('id_bahan', $detail['nama_bahan'])
                        ->where('harga_produk', $detail['harga_produk'])
                        ->delete();
                DB::table('detail_transaksi_pelanggans')->insert([
                    'id_transaksi'=>$addtransaksi,
                    'id_produk' => $detail['id_produk'],
                    'id_warna' => $detail['nama_warna'],
                    'id_bahan' => $detail['nama_bahan'],
                    'jumlah' => $detail['jumlah'],
                    'harga_produk' => $detail['harga_produk'],
                    'tambahan_motif'=> $detail['tambahan_motif']
                    ]);
            }
            return response()->json([
                'statusCode' => 200,
                'message' => 'Data inserted successfully'
            ]);
        } else {
            // Failed to insert data
            return response()->json([
                'statusCode' => 500,
                'message' => 'Failed to insert data'
            ]);
        }
    }
    public function insert_pesanan_admin(Request $request)
    {
        $tglSkrg = Carbon::now();
        $formattedTglSkrg = $tglSkrg->format('d-m-Y');
        $tgl_pesan = date('Y-m-d', strtotime($formattedTglSkrg));
        $id_user = Auth::user()->id;
        $status_transaksi = 'Belum Dibayar';
        $total_belanja = intval($request->total_belanja);
        $biaya_ongkir = intval($request->biaya_ongkir);

        
        $addtransaksi = DB::table('transaksi_admins')->insertGetId([
            'id_user'=>$id_user,
            'nama_pelanggan'=>$request->nama_pelanggan,
            'no_hp'=>$request->no_hp,
            'kirim_ekspedisi'=>$request->kirim_ekspedisi,
            'tgl_pesan'=>$tgl_pesan,
            'total_belanja'=>$total_belanja,
            'status_transaksi'=>$status_transaksi,
            'note_transaksi'=>$request->note_transaksi
            ]);
        if ($request->kirim_ekspedisi == true){
            DB::table('distribusi_barang_admins')->insertGetId([
                        'id_transaksi'=>$addtransaksi,
                        'biaya_ongkir'=>$biaya_ongkir,
                        'layanan_ekspedisi'=>$request->layanan_ekspedisi,
                        'alamat'=>$request->alamat,
                        'id_kecamatan'=>$request->kecamatan,
                        'kode_pos'=>$request->kode_pos,
        ]);
        }
        if ($addtransaksi) {
            // masukkan detail transaksi
            foreach ($request->dataArray as $detail) {
                // hapus isi keranjang
                DB::table('keranjangs')
                        ->where('id_produk', $detail['id_produk'])
                        ->where('id_warna', $detail['id_warna'])
                        ->where('id_bahan', $detail['id_bahan'])
                        ->where('harga_produk', $detail['harga_produk'])
                        ->delete();
                DB::table('detail_transaksi_admins')->insert([
                    'id_transaksi'=>$addtransaksi,
                    'id_produk' => $detail['id_produk'],
                    'id_warna' => $detail['id_warna'],
                    'id_bahan' => $detail['id_bahan'],
                    'jumlah' => $detail['jumlah'],
                    'harga_produk' => $detail['harga_produk'],
                    'tambahan_motif'=> $detail['tambahan_motif']
                    ]);
            }
            return response()->json([
                'statusCode' => 200,
                'message' => 'Data inserted successfully'
            ]);
        } else {
            // Failed to insert data
            return response()->json([
                'statusCode' => 500,
                'message' => 'Failed to insert data'
            ]);
        }
    }
    public function detail_pesanan_pelanggan ($id)
    {
        $data = DB::table('detail_transaksi_pelanggans')->where('id_transaksi', $id)
                    ->join('produks', 'produks.id_produk', '=', 'detail_transaksi_pelanggans.id_produk')
                    ->join('warnas', 'warnas.id', '=', 'detail_transaksi_pelanggans.id_warna')
                    ->join('bahans', 'bahans.id', '=', 'detail_transaksi_pelanggans.id_bahan')
                    ->get();
        $detail = DB::table('transaksi_pelanggans')->where('transaksi_pelanggans.id_transaksi', $id)
                    ->leftJoin('distribusi_barang_pelanggans', 'distribusi_barang_pelanggans.id_transaksi', '=', 'transaksi_pelanggans.id_transaksi')
                    ->leftJoin('alamat_pelanggans', 'alamat_pelanggans.id_alamat', '=', 'distribusi_barang_pelanggans.id_alamat')
                    ->leftJoin('indonesia_districts', 'indonesia_districts.id', '=', 'alamat_pelanggans.id_kecamatan')
                    ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'indonesia_districts.city_code')
                    ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'indonesia_cities.province_code')
                    ->select('distribusi_barang_pelanggans.*',
                            'transaksi_pelanggans.*',
                            'alamat_pelanggans.*', 
                            'indonesia_districts.name as nama_kecamatan',
                            'indonesia_cities.name as nama_kota',
                            'indonesia_provinces.name as nama_provinsi'
                            )
                    ->first();
        return view('pelanggan.detailpesananpelanggan', ['data'=>$data, 'detail'=>$detail]);
    }
    public function detail_pesanan_admin ($id)
    {
        $data = DB::table('detail_transaksi_admins')->where('id_transaksi', $id)
                    ->join('produks', 'produks.id_produk', '=', 'detail_transaksi_admins.id_produk')
                    ->join('warnas', 'warnas.id', '=', 'detail_transaksi_admins.id_warna')
                    ->join('bahans', 'bahans.id', '=', 'detail_transaksi_admins.id_bahan')
                    ->get();
        $detail = DB::table('transaksi_admins')
                    ->where('transaksi_admins.id_transaksi', $id)
                    ->leftJoin('distribusi_barang_admins', 'distribusi_barang_admins.id_transaksi', '=', 'transaksi_admins.id_transaksi')
                    ->join('users', 'users.id', '=', 'transaksi_admins.id_user')
                    ->select(
                        'transaksi_admins.*',
                        'users.id as id_user',
                        'users.role as role',
                        'distribusi_barang_admins.id_distribusi as id_distribusi'
                    )
                    ->selectRaw('COALESCE(distribusi_barang_admins.id_distribusi, 0) as distribusi_id')
                    ->first();
        $distribusi_id = $detail->distribusi_id;

        
        $distribusiBarangAdmin = DB::table('distribusi_barang_admins')
                ->where('id_distribusi', $distribusi_id)
                ->leftJoin('indonesia_districts', 'indonesia_districts.id', '=', 'distribusi_barang_admins.id_kecamatan')
                ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'indonesia_districts.city_code')
                ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'indonesia_cities.province_code')
                ->select('distribusi_barang_admins.*', 
                        'indonesia_districts.name as nama_kecamatan',
                        'indonesia_cities.name as nama_kota',
                        'indonesia_provinces.name as nama_provinsi'
                        )
                ->first();
        return view('admin.detailpesananadmin', compact('data', 'detail', 'distribusiBarangAdmin'));
    }
    public function detail_pesanan_adminbypel ($id)
    {
        $data = DB::table('detail_transaksi_pelanggans')->where('id_transaksi', $id)
                    ->join('produks', 'produks.id_produk', '=', 'detail_transaksi_pelanggans.id_produk')
                    ->join('warnas', 'warnas.id', '=', 'detail_transaksi_pelanggans.id_warna')
                    ->join('bahans', 'bahans.id', '=', 'detail_transaksi_pelanggans.id_bahan')
                    ->get();
        $detail = DB::table('transaksi_pelanggans')
                    ->where('transaksi_pelanggans.id_transaksi', $id)
                    ->leftJoin('distribusi_barang_pelanggans', 'distribusi_barang_pelanggans.id_transaksi', '=', 'transaksi_pelanggans.id_transaksi')
                    ->leftJoin('pelanggans', 'pelanggans.id_user','=', 'transaksi_pelanggans.id_user' )
                    ->join('users', 'users.id', '=', 'transaksi_pelanggans.id_user')
                    ->select(
                        'transaksi_pelanggans.*',
                        'pelanggans.*',
                        'users.id as id_user',
                        'users.role as role',
                        'distribusi_barang_pelanggans.id_distribusi as id_distribusi'
                    )
                    ->selectRaw('COALESCE(distribusi_barang_pelanggans.id_distribusi, 0) as distribusi_id')
                    ->first();
        $distribusi_id = $detail->distribusi_id;

        
        $distribusiBarangAdmin = DB::table('distribusi_barang_pelanggans')
                ->where('id_distribusi', $distribusi_id)
                ->leftJoin('alamat_pelanggans', 'alamat_pelanggans.id_alamat', '=', 'distribusi_barang_pelanggans.id_alamat')
                ->leftJoin('indonesia_districts', 'indonesia_districts.id', '=', 'alamat_pelanggans.id_kecamatan')
                ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'indonesia_districts.city_code')
                ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'indonesia_cities.province_code')
                ->select('distribusi_barang_pelanggans.*',
                        'alamat_pelanggans.*',
                        'indonesia_districts.name as nama_kecamatan',
                        'indonesia_cities.name as nama_kota',
                        'indonesia_provinces.name as nama_provinsi'
                        )
                ->first();
        return view('admin.detailpesananadmin', compact('data', 'detail', 'distribusiBarangAdmin'));
    }
    public function insert_bukti_bayar_adm(Request $request)
    {
        $id_user = Auth::user()->id;
        $validatedData = $request->validate([
            'buktibayar' => 'required|mimes:pdf,jpg,jpeg,png'
        ], [
            'buktibayar.mimes' => 'Format file tidak sesuai'
        ]);

        if ($validatedData){
            if($request->hasfile('buktibayar'))
            {
                $destinationPath = 'buktibayar/';
                $foto_name = date('YmdHis').'_'. $id_user . '.' . $request->file('buktibayar')->getClientOriginalExtension();
                $request->file('buktibayar')->move($destinationPath, $foto_name);
            }
            DB::table('transaksi_admins')->where('id_transaksi', $request->id_transaksi)
                        ->update([
                            'status_transaksi'=>'Menunggu Konfirmasi',
                            'bukti_bayar'=>$foto_name
                        ]);
        }else {
            return response()->json(['error' => 'Format file tidak benar']);
        }
    }
    public function insert_bukti_bayar_pel(Request $request)
    {
        $id_user = Auth::user()->id;
        $validatedData = $request->validate([
            'buktibayar' => 'required|mimes:pdf,jpg,jpeg,png'
        ], [
            'buktibayar.mimes' => 'Format file tidak sesuai'
        ]);

        if ($validatedData){
            if($request->hasfile('buktibayar'))
            {
                $destinationPath = 'buktibayar/';
                $foto_name = date('YmdHis').'_'. $id_user . '.' . $request->file('buktibayar')->getClientOriginalExtension();
                $request->file('buktibayar')->move($destinationPath, $foto_name);
            }
            DB::table('transaksi_pelanggans')->where('id_transaksi', $request->id_transaksi)
                        ->update([
                            'status_transaksi'=>'Menunggu Konfirmasi',
                            'bukti_bayar'=>$foto_name
                        ]);
        }else {
            return response()->json(['error' => 'Format file tidak benar']);
        }
    }
    public function batal_pesanan_admin($id)
    {
        DB::table('transaksi_admins')->where('id_transaksi', $id)
                    ->update([
                        'status_transaksi'=>'Dibatalkan'
                    ]);
        return redirect()->back();
    }
    public function batal_pesanan_pelanggan($id)
    {
        DB::table('transaksi_pelanggans')->where('id_transaksi', $id)
                    ->update([
                        'status_transaksi'=>'Dibatalkan'
                    ]);
        return redirect()->back();
    }
    public function selesai_pesanan_admin($id)
    {
        $tglSkrg = Carbon::now();
        $formattedTglSkrg = $tglSkrg->format('d-m-Y');
        $tgl_selesai = date('Y-m-d', strtotime($formattedTglSkrg));
        DB::table('transaksi_admins')->where('id_transaksi', $id)
                    ->update([
                        'tgl_selesai'=>$tgl_selesai,
                        'status_transaksi'=>'Selesai'
                    ]);
        return redirect()->back();
    }
    public function selesai_pesanan_pelanggan($id)
    {
        $tglSkrg = Carbon::now();
        $formattedTglSkrg = $tglSkrg->format('d-m-Y');
        $tgl_selesai = date('Y-m-d', strtotime($formattedTglSkrg));
        DB::table('transaksi_pelanggans')->where('id_transaksi', $id)
                    ->update([
                        'tgl_selesai'=>$tgl_selesai,
                        'status_transaksi'=>'Selesai'
                    ]);
        return redirect()->back();
    }
    public function selesai_pesanan_pelangganbypel($id)
    {
        DB::table('transaksi_pelanggans')->where('id_transaksi', $id)
                    ->update([
                        'status_transaksi'=>'Sudah Diterima'
                    ]);
        return redirect()->back();
    }
    public function update_status_admin(Request $request)
    {
        $request->validate([
            'id_transaksi'=> 'required',
            'status_transaksi'=> 'required'
        ]);

        $id=$request->id_transaksi;
        $status=$request->status_transaksi;
        DB::table('transaksi_admins')->where('id_transaksi', $id)
                    ->update([
                        'status_transaksi'=>$status
                    ]);
    }
    public function update_status_pelanggan(Request $request)
    {
        $request->validate([
            'id_transaksi'=> 'required',
            'status_transaksi'=> 'required'
        ]);

        $id=$request->id_transaksi;
        $status=$request->status_transaksi;
        DB::table('transaksi_pelanggans')->where('id_transaksi', $id)
                    ->update([
                        'status_transaksi'=>$status
                    ]);
    }
    public function generateInvoicePdfAdm($id)
    {
        $data = DB::table('detail_transaksi_admins')
        ->where('id_transaksi', $id)
        ->join('produks', 'produks.id_produk', '=', 'detail_transaksi_admins.id_produk')
        ->get();

        $detail = DB::table('transaksi_admins')
                ->where('transaksi_admins.id_transaksi', $id)
                ->join('users', 'users.id', '=', 'transaksi_admins.id_user')
                ->leftJoin('distribusi_barang_admins', 'distribusi_barang_admins.id_transaksi', '=', 'transaksi_admins.id_transaksi')
                ->leftJoin('indonesia_districts', 'indonesia_districts.id', '=', 'distribusi_barang_admins.id_kecamatan')
                ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'indonesia_districts.city_code')
                ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'indonesia_cities.province_code')
                ->select([
                    'transaksi_admins.*',
                    'users.id as id_user',
                    'users.role as role',
                    DB::raw('IFNULL(distribusi_barang_admins.id_transaksi, 0) as id_distribusi'),
                    DB::raw('IFNULL(distribusi_barang_admins.biaya_ongkir, 0) as biaya_ongkir'),
                    DB::raw('IFNULL(distribusi_barang_admins.alamat, "Default Kecamatan") as alamat'),
                    DB::raw('IFNULL(distribusi_barang_admins.kode_pos, "Default Kode Pos") as kode_pos'),
                    DB::raw('IFNULL(indonesia_districts.name, "Default Kecamatan") as kecamatan_tujuan'),
                    DB::raw('IFNULL(indonesia_cities.name, "Default Kota") as kota_tujuan'),
                    DB::raw('IFNULL(indonesia_provinces.name, "Default Provinsi") as provinsi_tujuan')
                    ])
                ->first();

        // dd($detail);
        
        // generate pdf
        $pdf = app('dompdf.wrapper')->loadView('admin.invoice', compact('data', 'detail'));

        return $pdf->stream('invoice.pdf');
    }
    public function generateInvoicePdfPel($id)
    {
        $data = DB::table('detail_transaksi_pelanggans')
        ->where('id_transaksi', $id)
        ->join('produks', 'produks.id_produk', '=', 'detail_transaksi_pelanggans.id_produk')
        ->get();

        $detail = DB::table('transaksi_pelanggans')
                ->where('transaksi_pelanggans.id_transaksi', $id)
                ->join('users', 'users.id', '=', 'transaksi_pelanggans.id_user')
                ->leftJoin('pelanggans', 'pelanggans.id_user', '=', 'users.id')
                ->leftJoin('distribusi_barang_pelanggans', 'distribusi_barang_pelanggans.id_transaksi', '=', 'transaksi_pelanggans.id_transaksi')
                ->leftJoin('alamat_pelanggans', 'alamat_pelanggans.id_alamat', '=', 'distribusi_barang_pelanggans.id_alamat')
                ->leftJoin('indonesia_districts', 'indonesia_districts.id', '=', 'alamat_pelanggans.id_kecamatan')
                ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'indonesia_districts.city_code')
                ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'indonesia_cities.province_code')
                ->select([
                    'transaksi_pelanggans.*',
                    'pelanggans.*',
                    'users.id as id_user',
                    'users.role as role',
                    DB::raw('IFNULL(distribusi_barang_pelanggans.id_transaksi, 0) as id_distribusi'),
                    DB::raw('IFNULL(distribusi_barang_pelanggans.biaya_ongkir, 0) as biaya_ongkir'),
                    DB::raw('IFNULL(alamat_pelanggans.alamat, "Default Kecamatan") as alamat'),
                    DB::raw('IFNULL(alamat_pelanggans.kode_pos, "Default Kode Pos") as kode_pos'),
                    DB::raw('IFNULL(indonesia_districts.name, "Default Kecamatan") as kecamatan_tujuan'),
                    DB::raw('IFNULL(indonesia_cities.name, "Default Kota") as kota_tujuan'),
                    DB::raw('IFNULL(indonesia_provinces.name, "Default Provinsi") as provinsi_tujuan')
                    ])
                ->first();
            // dd($detail);
        
        // generate pdf
        $pdf = app('dompdf.wrapper')->loadView('admin.invoice', compact('data', 'detail'));

        return $pdf->stream('invoice.pdf');
    }
}

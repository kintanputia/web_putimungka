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
        $data = DB::table('transaksis')->orderby('transaksis.id_transaksi', 'desc')
                            ->leftJoin('pelanggans', 'pelanggans.id_pelanggan', '=', 'transaksis.id_pelanggan')
                            ->where('pelanggans.id_user', $id_user)
                            ->paginate(10);
        return view('pelanggan.daftarpesananpelanggan', ['data'=>$data]);
    }
    public function index_pesananadm(Request $request)
    {
        $id_admin = Auth::user()->id;
        $status = $request->input('status');
        $query1 = DB::table('transaksis as ta')
                        ->leftJoin('pelanggans', 'pelanggans.id_pelanggan', '=', 'ta.id_pelanggan')
                        ->leftJoin('users', 'users.id', '=', 'pelanggans.id_user')
                        ->orderBy('ta.id_transaksi', 'desc')
                        ->where('users.role', '=', 'admin');

        $query2 = DB::table('transaksis as tp')
                        ->leftJoin('pelanggans', 'pelanggans.id_pelanggan', '=', 'tp.id_pelanggan')
                        ->leftJoin('users', 'users.id', '=', 'pelanggans.id_user')
                        ->orderBy('tp.id_transaksi', 'desc')
                        ->where('users.role', '=', 'pelanggan');
        if ($status && $status !== 'all') {
            $query1->where('status_transaksi', $status);
            $query2->where('status_transaksi', $status);
        }
        $data_admin = $query1->paginate(10);
        $data_pelanggan = $query2->paginate(10);
        return view('admin.pesananadmin', compact('data_admin', 'data_pelanggan', 'status'));
    }
    public function insert_pesanan(Request $request)
    {
        $id_alamat = $request->id_alamat;
        $tglSkrg = Carbon::now();
        $formattedTglSkrg = $tglSkrg->format('d-m-Y');
        $tgl_pesan = date('Y-m-d', strtotime($formattedTglSkrg));
        $id_user = Auth::user()->id;
        $id_pelanggan = DB::table('pelanggans')->where('id_user', $id_user)->value('id_pelanggan');
        $status_transaksi = 'Belum Dibayar';
        $total_belanja = intval($request->total_belanja);
        $biaya_ongkir = intval($request->biaya_ongkir);

        $alamat = DB::table('alamat_pelanggans')->where('id_alamat', $id_alamat)->first();

        $addtransaksi = DB::table('transaksis')->insertGetId([
            'id_pelanggan'=>$id_pelanggan,
            'kirim_ekspedisi'=>$request->kirim_ekspedisi,
            'tgl_pesan'=>$tgl_pesan,
            'total_belanja'=>$total_belanja,
            'status_transaksi'=>$status_transaksi,
            'note_transaksi'=>$request->note_transaksi
            ]);
        
        if ($request->kirim_ekspedisi == true){
            DB::table('distribusi_barangs')->insertGetId([
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
                DB::table('detail_transaksis')->insert([
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

        $addPelanggan  = DB::table('pelanggans')->insertGetId([
            'id_user' =>$id_user,
            'nama_pelanggan'=>$request->nama_pelanggan,
            'no_hp'=>$request->no_hp,
        ]);

        $addtransaksi = DB::table('transaksis')->insertGetId([
            'id_pelanggan'=>$addPelanggan,
            'kirim_ekspedisi'=>$request->kirim_ekspedisi,
            'tgl_pesan'=>$tgl_pesan,
            'total_belanja'=>$total_belanja,
            'status_transaksi'=>$status_transaksi,
            'note_transaksi'=>$request->note_transaksi
            ]);
        if ($request->kirim_ekspedisi == true){
            $addAlamat = DB::table('alamat_pelanggans')->insertGetId([
                'id_pelanggan'=>$addPelanggan,
                'alamat'=>$request->alamat,
                'id_kecamatan'=>$request->kecamatan,
                'kode_pos'=>$request->kode_pos
                ]);
            DB::table('distribusi_barangs')->insert([
                        'id_transaksi'=>$addtransaksi,
                        'id_alamat'=>$addAlamat,
                        'biaya_ongkir'=>$biaya_ongkir,
                        'layanan_ekspedisi'=>$request->layanan_ekspedisi
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
                DB::table('detail_transaksis')->insert([
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
        $data = DB::table('detail_transaksis')->where('id_transaksi', $id)
                    ->join('produks', 'produks.id_produk', '=', 'detail_transaksis.id_produk')
                    ->join('warnas', 'warnas.id', '=', 'detail_transaksis.id_warna')
                    ->join('bahans', 'bahans.id', '=', 'detail_transaksis.id_bahan')
                    ->get();
        $waktu_pengerjaan = DB::table('detail_transaksis')
                        ->where('id_transaksi', $id)
                        ->join('produks', 'produks.id_produk', '=', 'detail_transaksis.id_produk')
                        ->select('produks.waktu_pengerjaan as max_waktu_pengerjaan')
                        ->max('produks.waktu_pengerjaan');
        $detail = DB::table('transaksis')->where('transaksis.id_transaksi', $id)
                    ->leftJoin('distribusi_barangs', 'distribusi_barangs.id_transaksi', '=', 'transaksis.id_transaksi')
                    ->leftJoin('alamat_pelanggans', 'alamat_pelanggans.id_alamat', '=', 'distribusi_barangs.id_alamat')
                    ->leftJoin('indonesia_districts', 'indonesia_districts.id', '=', 'alamat_pelanggans.id_kecamatan')
                    ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'indonesia_districts.city_code')
                    ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'indonesia_cities.province_code')
                    ->select('distribusi_barangs.*',
                            'transaksis.*',
                            'alamat_pelanggans.*', 
                            'indonesia_districts.name as nama_kecamatan',
                            'indonesia_cities.name as nama_kota',
                            'indonesia_provinces.name as nama_provinsi'
                            )
                    ->first();
        // tambah data waktu pengerjaan
        $detail->max_waktu_pengerjaan = $waktu_pengerjaan;
        return view('pelanggan.detailpesananpelanggan', ['data'=>$data, 'detail'=>$detail]);
    }
    public function detail_pesanan_admin ($id)
    {
        $data = DB::table('detail_transaksis')->where('id_transaksi', $id)
                    ->join('produks', 'produks.id_produk', '=', 'detail_transaksis.id_produk')
                    ->join('warnas', 'warnas.id', '=', 'detail_transaksis.id_warna')
                    ->join('bahans', 'bahans.id', '=', 'detail_transaksis.id_bahan')
                    ->get();
        $waktu_pengerjaan = DB::table('detail_transaksis')
                        ->where('id_transaksi', $id)
                        ->join('produks', 'produks.id_produk', '=', 'detail_transaksis.id_produk')
                        ->select('produks.waktu_pengerjaan as max_waktu_pengerjaan')
                        ->max('produks.waktu_pengerjaan');
        $detail = DB::table('transaksis')
                    ->where('transaksis.id_transaksi', $id)
                    ->leftJoin('distribusi_barangs', 'distribusi_barangs.id_transaksi', '=', 'transaksis.id_transaksi')
                    ->join('pelanggans', 'pelanggans.id_pelanggan', '=', 'transaksis.id_pelanggan')
                    ->join('users', 'users.id', '=', 'pelanggans.id_user')
                    ->select(
                        'transaksis.*',
                        'pelanggans.*',
                        'users.id as id_user',
                        'users.role as role',
                        'distribusi_barangs.id_distribusi as id_distribusi'
                    )
                    ->selectRaw('COALESCE(distribusi_barangs.id_distribusi, 0) as distribusi_id')
                    ->first();
        $distribusi_id = $detail->distribusi_id;
        
        $distribusiBarangAdmin = DB::table('distribusi_barangs')
                ->where('id_distribusi', $distribusi_id)
                ->leftJoin('alamat_pelanggans', 'alamat_pelanggans.id_alamat', '=', 'distribusi_barangs.id_alamat')
                ->leftJoin('indonesia_districts', 'indonesia_districts.id', '=', 'alamat_pelanggans.id_kecamatan')
                ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'indonesia_districts.city_code')
                ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'indonesia_cities.province_code')
                ->select('distribusi_barangs.*',
                        'alamat_pelanggans.*',
                        'indonesia_districts.name as nama_kecamatan',
                        'indonesia_cities.name as nama_kota',
                        'indonesia_provinces.name as nama_provinsi'
                        )
                ->first();

        // tambah data waktu pengerjaan
        $detail->max_waktu_pengerjaan = $waktu_pengerjaan;
        return view('admin.detailpesananadmin', compact('data', 'detail', 'distribusiBarangAdmin'));
    }
    public function detail_pesanan_adminbypel ($id)
    {
        $data = DB::table('detail_transaksis')->where('id_transaksi', $id)
                    ->join('produks', 'produks.id_produk', '=', 'detail_transaksis.id_produk')
                    ->join('warnas', 'warnas.id', '=', 'detail_transaksis.id_warna')
                    ->join('bahans', 'bahans.id', '=', 'detail_transaksis.id_bahan')
                    ->get();
        $waktu_pengerjaan = DB::table('detail_transaksis')
                        ->where('id_transaksi', $id)
                        ->join('produks', 'produks.id_produk', '=', 'detail_transaksis.id_produk')
                        ->select('produks.waktu_pengerjaan as max_waktu_pengerjaan')
                        ->max('produks.waktu_pengerjaan');
        $detail = DB::table('transaksis')
                    ->where('transaksis.id_transaksi', $id)
                    ->leftJoin('distribusi_barangs', 'distribusi_barangs.id_transaksi', '=', 'transaksis.id_transaksi')
                    ->leftJoin('pelanggans', 'pelanggans.id_pelanggan','=', 'transaksis.id_pelanggan' )
                    ->join('users', 'users.id', '=', 'pelanggans.id_user')
                    ->select(
                        'transaksis.*',
                        'pelanggans.*',
                        'users.id as id_user',
                        'users.role as role',
                        'distribusi_barangs.id_distribusi as id_distribusi'
                    )
                    ->selectRaw('COALESCE(distribusi_barangs.id_distribusi, 0) as distribusi_id')
                    ->first();
        $distribusi_id = $detail->distribusi_id;

        
        $distribusiBarangAdmin = DB::table('distribusi_barangs')
                ->where('id_distribusi', $distribusi_id)
                ->leftJoin('alamat_pelanggans', 'alamat_pelanggans.id_alamat', '=', 'distribusi_barangs.id_alamat')
                ->leftJoin('indonesia_districts', 'indonesia_districts.id', '=', 'alamat_pelanggans.id_kecamatan')
                ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'indonesia_districts.city_code')
                ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'indonesia_cities.province_code')
                ->select('distribusi_barangs.*',
                        'alamat_pelanggans.*',
                        'indonesia_districts.name as nama_kecamatan',
                        'indonesia_cities.name as nama_kota',
                        'indonesia_provinces.name as nama_provinsi'
                        )
                ->first();

        // tambah data waktu pengerjaan
        $detail->max_waktu_pengerjaan = $waktu_pengerjaan;
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
            DB::table('transaksis')->where('id_transaksi', $request->id_transaksi)
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
            DB::table('transaksis')->where('id_transaksi', $request->id_transaksi)
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
        DB::table('transaksis')->where('id_transaksi', $id)
                    ->update([
                        'status_transaksi'=>'Dibatalkan'
                    ]);
        return redirect()->back();
    }
    public function batal_pesanan_pelanggan($id)
    {
        DB::table('transaksis')->where('id_transaksi', $id)
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
        DB::table('transaksis')->where('id_transaksi', $id)
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
        DB::table('transaksis')->where('id_transaksi', $id)
                    ->update([
                        'tgl_selesai'=>$tgl_selesai,
                        'status_transaksi'=>'Selesai'
                    ]);
        return redirect()->back();
    }
    public function selesai_pesanan_pelangganbypel($id)
    {
        DB::table('transaksis')->where('id_transaksi', $id)
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
        DB::table('transaksis')->where('id_transaksi', $id)
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
        DB::table('transaksis')->where('id_transaksi', $id)
                    ->update([
                        'status_transaksi'=>$status
                    ]);
    }
    public function generateInvoicePdfAdm($id)
    {
        $data = DB::table('detail_transaksis')
        ->where('id_transaksi', $id)
        ->join('produks', 'produks.id_produk', '=', 'detail_transaksis.id_produk')
        ->get();

        $detail = DB::table('transaksis')
                ->where('transaksis.id_transaksi', $id)
                ->join('pelanggans', 'pelanggans.id_pelanggan', '=', 'transaksis.id_pelanggan')
                ->join('users', 'users.id', '=', 'pelanggans.id_user')
                ->leftJoin('distribusi_barangs', 'distribusi_barangs.id_transaksi', '=', 'transaksis.id_transaksi')
                ->leftJoin('alamat_pelanggans', 'alamat_pelanggans.id_alamat', '=', 'distribusi_barangs.id_alamat')
                ->leftJoin('indonesia_districts', 'indonesia_districts.id', '=', 'alamat_pelanggans.id_kecamatan')
                ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'indonesia_districts.city_code')
                ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'indonesia_cities.province_code')
                ->select([
                    'transaksis.*',
                    'pelanggans.*',
                    'alamat_pelanggans.*',
                    'users.id as id_user',
                    'users.role as role',
                    DB::raw('IFNULL(distribusi_barangs.id_transaksi, 0) as id_distribusi'),
                    DB::raw('IFNULL(distribusi_barangs.biaya_ongkir, 0) as biaya_ongkir'),
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
    public function generateInvoicePdfPel($id)
    {
        $data = DB::table('detail_transaksis')
        ->where('id_transaksi', $id)
        ->join('produks', 'produks.id_produk', '=', 'detail_transaksis.id_produk')
        ->get();

        $detail = DB::table('transaksis')
                ->where('transaksis.id_transaksi', $id)
                ->join('users', 'users.id', '=', 'transaksis.id_user')
                ->leftJoin('pelanggans', 'pelanggans.id_user', '=', 'users.id')
                ->leftJoin('distribusi_barangs', 'distribusi_barangs.id_transaksi', '=', 'transaksis.id_transaksi')
                ->leftJoin('alamat_pelanggans', 'alamat_pelanggans.id_alamat', '=', 'distribusi_barangs.id_alamat')
                ->leftJoin('indonesia_districts', 'indonesia_districts.id', '=', 'alamat_pelanggans.id_kecamatan')
                ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'indonesia_districts.city_code')
                ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'indonesia_cities.province_code')
                ->select([
                    'transaksis.*',
                    'pelanggans.*',
                    'alamat_pelanggans.*',
                    'users.id as id_user',
                    'users.role as role',
                    DB::raw('IFNULL(distribusi_barangs.id_transaksi, 0) as id_distribusi'),
                    DB::raw('IFNULL(distribusi_barangs.biaya_ongkir, 0) as biaya_ongkir'),
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

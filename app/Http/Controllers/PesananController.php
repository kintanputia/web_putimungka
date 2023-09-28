<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PesananController extends Controller
{
    public function index_pesanan()
    {
        $id_user = Auth::user()->id;
        $data = DB::table('transaksis')->orderby('transaksis.id', 'desc')->where('id_user', $id_user)->get();
        return view('pelanggan.daftarpesananpelanggan', ['data'=>$data]);
    }
    public function index_pesananadm()
    {
        $data = DB::table('transaksis')->orderby('transaksis.id', 'desc')->get();
        return view('admin.pesananadmin', ['data'=>$data]);
    }
    public function insert_pesanan(Request $request)
    {
        $tglSkrg = Carbon::now();
        $formattedTglSkrg = $tglSkrg->format('d-m-Y');
        $tgl_pesan = date('Y-m-d', strtotime($formattedTglSkrg));
        $id_user = Auth::user()->id;
        $status_transaksi = 'Belum Dibayar';
        $total_belanja = intval($request->total_belanja);
        $biaya_ongkir = intval($request->biaya_ongkir);
        $addtransaksi = DB::table('transaksis')->insertGetId([
            'id_user'=>$id_user,
            'nama_pelanggan'=>$request->nama_pelanggan,
            'no_hp'=>$request->no_hp,
            'tgl_pesan'=>$tgl_pesan,
            'total_belanja'=>$total_belanja,
            'kirim_ekspedisi'=>$request->kirim_ekspedisi,
            'biaya_ongkir'=>$biaya_ongkir,
            'layanan_ekspedisi'=>$request->layanan_ekspedisi,
            'status_transaksi'=>$status_transaksi,
            'alamat'=>$request->alamat,
            'kecamatan_tujuan'=>$request->kecamatan,
            'kota_tujuan'=>$request->kota,
            'provinsi_tujuan'=>$request->provinsi,
            'kode_pos'=>$request->kode_pos,
            'note_transaksi'=>$request->note_transaksi
            ]);
        
        if ($addtransaksi) {
            // masukkan detail transaksi
            foreach ($request->dataArray as $detail) {
                // hapus isi keranjang
                DB::table('keranjangs')
                        ->where('id_produk', $detail['id_produk'])
                        ->where('nama_warna', $detail['nama_warna'])
                        ->where('nama_bahan', $detail['nama_bahan'])
                        ->where('harga_produk', $detail['harga_produk'])
                        ->delete();
                DB::table('detail_transaksis')->insert([
                    'id_transaksi'=>$addtransaksi,
                    'id_produk' => $detail['id_produk'],
                    'nama_warna' => $detail['nama_warna'],
                    'nama_bahan' => $detail['nama_bahan'],
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
        $data = DB::table('detail_transaksis')->orderby('detail_transaksis.id', 'desc')->where('id_transaksi', $id)
                    ->join('produks', 'produks.id', '=', 'detail_transaksis.id_produk')
                    ->get();
        $detail = DB::table('transaksis')->where('id', $id)->first();
        return view('pelanggan.detailpesananpelanggan', ['data'=>$data, 'detail'=>$detail]);
    }
    public function detail_pesanan_admin ($id)
    {
        $data = DB::table('detail_transaksis')->orderby('detail_transaksis.id', 'desc')->where('id_transaksi', $id)
                    ->join('produks', 'produks.id', '=', 'detail_transaksis.id_produk')
                    ->get();
        $detail = DB::table('transaksis')->where('transaksis.id', $id)
                    ->join('users', 'users.id', '=', 'transaksis.id_user')
                    ->select(
                        'transaksis.*',
                        'users.id as id_user',
                        'users.role as role'
                    )
                    ->first();
        return view('admin.detailpesananadmin', ['data'=>$data, 'detail'=>$detail]);
    }
    public function insert_bukti_bayar(Request $request)
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
            DB::table('transaksis')->where('id', $request->id_transaksi)
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
        DB::table('transaksis')->where('id', $id)
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
        DB::table('transaksis')->where('id', $id)
                    ->update([
                        'tgl_selesai'=>$tgl_selesai,
                        'status_transaksi'=>'Selesai'
                    ]);
        return redirect()->back();
    }
    public function selesai_pesanan_pelanggan($id)
    {
        DB::table('transaksis')->where('id', $id)
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
        DB::table('transaksis')->where('id', $id)
                    ->update([
                        'status_transaksi'=>$status
                    ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\TransaksiAdmin;
use App\Models\TransaksiPelanggan;

class HasilPenjualanController extends Controller
{
    public function index_penjualan(Request $request){
        try {
            $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $currentYear = date('Y');
            $uniqueYears = DB::table('transaksis')
                        ->select('tgl_selesai')
                        ->whereNotNull('tgl_selesai')
                        ->pluck('tgl_selesai')->map(function ($date) {
                return \Carbon\Carbon::parse($date)->year;
            })->unique();

            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
    
            $admin = DB::table('transaksis')
                ->whereNotNull('tgl_selesai')
                ->orderBy('transaksis.id_transaksi', 'desc')
                ->join('pelanggans', 'pelanggans.id_pelanggan', '=', 'transaksis.id_pelanggan')
                ->join('users', 'users.id', '=', 'pelanggans.id_user')
                ->where('users.role', '=', 'admin');
            $pelanggan = DB::table('transaksis')
                ->whereNotNull('tgl_selesai')
                ->orderBy('transaksis.id_transaksi', 'desc')
                ->join('pelanggans', 'pelanggans.id_pelanggan', '=', 'transaksis.id_pelanggan')
                ->join('users', 'users.id', '=', 'pelanggans.id_user')
                ->where('users.role', '=', 'pelanggan');
    
            if ($start_date && $end_date) {
                $admin->whereBetween('tgl_selesai', [$start_date, $end_date]);
                $pelanggan->whereBetween('tgl_selesai', [$start_date, $end_date]);
            }
    
            $penjualan_admin = $admin->get();
            $penjualan_pelanggan = $pelanggan->get();
    
            return view('admin.laporanpenjualan', compact('penjualan_admin', 'penjualan_pelanggan', 'start_date', 'end_date', 'uniqueYears', 'currentYear'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }
    public function getMonthlySalesData(Request $request, $year)
    {
        $monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        $monthlySalesData1 = DB::table('transaksis')
                            ->select(
                                DB::raw('MONTH(tgl_selesai) as month'),
                                DB::raw('SUM(total_belanja) as total_sales')
                            )
                            ->whereNotNull('tgl_selesai')
                            ->whereYear('tgl_selesai', $year)
                            ->groupBy(DB::raw('MONTH(tgl_selesai)'))
                            ->orderBy('month')
                            ->pluck('total_sales', 'month')
                            ->toArray();

        // $monthlySalesData2 = TransaksiPelanggan::select(
        //                         DB::raw('MONTH(tgl_selesai) as month'),
        //                         DB::raw('SUM(total_belanja) as total_sales')
        //                     )
        //                     ->whereNotNull('tgl_selesai')
        //                     ->whereYear('tgl_selesai', $year)
        //                     ->groupBy(DB::raw('MONTH(tgl_selesai)'))
        //                     ->orderBy('month')
        //                     ->pluck('total_sales', 'month')->toArray();

        // $transaksiMerge = array_merge($monthlySalesData1, $monthlySalesData2);
        $totalMonthlySales = array_sum($monthlySalesData1);
        $data = [];
        foreach ($monthNames as $key => $monthName) {
            $totalSales = ($monthlySalesData1[$key + 1] ?? 0);
            $data[$monthName] = $totalSales;
        }

        return response()->json([
            'data' => $data,
            'totalMonthlySales' => $totalMonthlySales
        ]);
    }
    public function generateLaporanPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $laporan = DB::table('transaksis')
            ->join('pelanggans', 'pelanggans.id_pelanggan', '=', 'transaksis.id_pelanggan')
            ->select('id_transaksi', 'nama_pelanggan', 'tgl_selesai', 'total_belanja')
            ->whereNotNull('tgl_selesai');

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $monthlySalesData1 = DB::table('transaksis')
            ->select(
                DB::raw('MONTH(tgl_selesai) as month'),
                DB::raw('SUM(total_belanja) as total_sales')
            )
            ->whereNotNull('tgl_selesai');

        if ($start_date && $end_date) {
            $laporan->whereBetween('tgl_selesai', [$start_date, $end_date]);
            $monthlySalesData1->whereBetween('tgl_selesai', [$start_date, $end_date]);
        }

        $penjualan = $laporan->get();
        $monthlySalesDataResults = $monthlySalesData1
            ->groupBy(DB::raw('MONTH(tgl_selesai)'))
            ->orderBy(DB::raw('MONTH(tgl_selesai)'))
            ->get();

        $monthlySalesData = $monthlySalesDataResults->pluck('total_sales', 'month')->toArray();
        $detail = array_sum($monthlySalesData);
        
        // generate pdf
        $pdf = app('dompdf.wrapper')->loadView('admin.laporan', compact('penjualan', 'detail'));
        
        return $pdf->stream('laporan.pdf');
    }
}

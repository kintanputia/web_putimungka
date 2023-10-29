<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\TransaksiAdmin;
use App\Models\TransaksiPelanggan;

class DashboardController extends Controller
{
    public function landing_page(){
        $data = DB::table('produks')->orderby('id_produk', 'desc')->take(3)->get();
        return view('landingpage', ['produk'=>$data]);
    }
    public function dashboard(){
        $data = DB::table('produks')->orderby('id_produk', 'desc')->take(3)->get();
        return view('pelanggan.dashboard', ['produk'=>$data]);
    }
    public function dashboard_admin(Request $request){
        try {
            $currentYear = date('Y');
            $monthlySalesData1 = TransaksiAdmin::select(
                                    DB::raw('MONTH(tgl_selesai) as month'),
                                    DB::raw('SUM(total_belanja) as total_sales')
                                )
                                ->whereNotNull('tgl_selesai')
                                ->whereYear('tgl_selesai', $currentYear)
                                ->groupBy(DB::raw('MONTH(tgl_selesai)'))
                                ->orderBy('month')
                                ->pluck('total_sales', 'month')->toArray();
            $uniqueYears = DB::table('transaksi_admins')
                        ->select('tgl_selesai')
                        ->whereNotNull('tgl_selesai')
                        ->union(
                            DB::table('transaksi_pelanggans')
                                ->select('tgl_selesai')
                                ->whereNotNull('tgl_selesai')
                        )
                        ->pluck('tgl_selesai')->map(function ($date) {
                return \Carbon\Carbon::parse($date)->year;
            })->unique();

            $totalMonthlySales = array_sum($monthlySalesData1);
            
            $pesanan_admin = DB::table('transaksi_admins')->orderBy('transaksi_admins.id_transaksi', 'desc')->take(10)->get();
            $pesanan_pelanggan = DB::table('transaksi_pelanggans')->orderBy('transaksi_pelanggans.id_transaksi', 'desc')
                                    ->leftJoin('pelanggans', 'pelanggans.id_user', '=', 'transaksi_pelanggans.id_user')
                                    ->take(10)->get();
            
            return view('admin.dashboardadmin', compact('pesanan_admin', 'pesanan_pelanggan', 'uniqueYears', 'currentYear', 'totalMonthlySales'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }
    public function getMonthlySalesData(Request $request, $year)
    {
        $monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        $monthlySalesData1 = TransaksiAdmin::select(
                                DB::raw('MONTH(tgl_selesai) as month'),
                                DB::raw('SUM(total_belanja) as total_sales')
                            )
                            ->whereNotNull('tgl_selesai')
                            ->whereYear('tgl_selesai', $year)
                            ->groupBy(DB::raw('MONTH(tgl_selesai)'))
                            ->orderBy('month')
                            ->pluck('total_sales', 'month')->toArray();

        $monthlySalesData2 = TransaksiPelanggan::select(
                                DB::raw('MONTH(tgl_selesai) as month'),
                                DB::raw('SUM(total_belanja) as total_sales')
                            )
                            ->whereNotNull('tgl_selesai')
                            ->whereYear('tgl_selesai', $year)
                            ->groupBy(DB::raw('MONTH(tgl_selesai)'))
                            ->orderBy('month')
                            ->pluck('total_sales', 'month')->toArray();

        $transaksiMerge = array_merge($monthlySalesData1, $monthlySalesData2);
        $totalMonthlySales = array_sum($transaksiMerge);
        $data = [];
        foreach ($monthNames as $key => $monthName) {
            $data[$monthName] = $monthlySalesData1[$key + 1] ?? 0;
        }
        return response()->json(['data' => $data, 'totalMonthlySales' => $totalMonthlySales]);
    }
}

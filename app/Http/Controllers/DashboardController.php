<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function landing_page(){
        $produk = DB::table('produks')
            ->orderBy('id_produk', 'desc')->take(3)
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
        return view('landingpage', ['produk'=>$produk]);
    }
    public function dashboard(){
        $produk = DB::table('produks')
            ->orderBy('id_produk', 'desc')->take(3)
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
        return view('pelanggan.dashboard', ['produk'=>$produk]);
    }
    public function dashboard_admin(Request $request){
        try {
            $currentYear = date('Y');
            $monthlySalesData1 = Transaksi::select(
                                    DB::raw('MONTH(tgl_selesai) as month'),
                                    DB::raw('SUM(total_belanja) as total_sales')
                                )
                                ->whereNotNull('tgl_selesai')
                                ->whereYear('tgl_selesai', $currentYear)
                                ->groupBy(DB::raw('MONTH(tgl_selesai)'))
                                ->orderBy('month')
                                ->pluck('total_sales', 'month')->toArray();
            $uniqueYears = DB::table('transaksis')
                        ->select('tgl_selesai')
                        ->whereNotNull('tgl_selesai')
                        ->pluck('tgl_selesai')->map(function ($date) {
                return \Carbon\Carbon::parse($date)->year;
            })->unique();

            $totalMonthlySales = array_sum($monthlySalesData1);
            
            $pesanan_admin = DB::table('transaksis')->orderBy('transaksis.id_transaksi', 'desc')
                                        ->leftJoin('pelanggans', 'pelanggans.id_pelanggan', '=', 'transaksis.id_pelanggan')
                                        ->leftJoin('users', 'pelanggans.id_user', '=', 'users.id')
                                        ->where('users.role', '=', 'admin')
                                        ->take(10)->get();
            $pesanan_pelanggan = DB::table('transaksis')->orderBy('transaksis.id_transaksi', 'desc')
                                        ->leftJoin('pelanggans', 'pelanggans.id_pelanggan', '=', 'transaksis.id_pelanggan')
                                        ->leftJoin('users', 'pelanggans.id_user', '=', 'users.id')
                                        ->where('users.role', '=', 'pelanggan')
                                        ->take(10)->get();
            
            return view('admin.dashboardadmin', compact('pesanan_admin', 'pesanan_pelanggan', 'uniqueYears', 'currentYear', 'totalMonthlySales'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }
    public function getMonthlySalesData(Request $request, $year)
    {
        $monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        $monthlySalesData1 = Transaksi::select(
                                DB::raw('MONTH(tgl_selesai) as month'),
                                DB::raw('SUM(total_belanja) as total_sales')
                            )
                            ->whereNotNull('tgl_selesai')
                            ->whereYear('tgl_selesai', $year)
                            ->groupBy(DB::raw('MONTH(tgl_selesai)'))
                            ->orderBy('month')
                            ->pluck('total_sales', 'month')->toArray();

        $totalMonthlySales = array_sum($monthlySalesData1);
        $data = [];
        foreach ($monthNames as $key => $monthName) {
            $data[$monthName] = $monthlySalesData1[$key + 1] ?? 0;
        }
        return response()->json(['data' => $data, 'totalMonthlySales' => $totalMonthlySales]);
    }
}

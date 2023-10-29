<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HasilPenjualanController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PesananController;

// umum
Route::get('/', [DashboardController::class,'landing_page']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/postLogin', [AuthController::class, 'postLogin']);
Route::get('/katalogproduktl', [KatalogController::class,'index_tl'])->name('katalogproduktl');
Route::get('/katalogproduktl/{id}', [KatalogController::class,'detail_produk_tl'])->name('detailkatalogtl');

// Admin
Route::get('/getMonthlySalesData/{year}', [HasilPenjualanController::class, 'getMonthlySalesData']);
Route::get('/dashboardadmin', [DashboardController::class,'dashboard_admin'])->middleware(['auth'])->name('dashboardadmin');
Route::get('/pengguna', [UserController::class,'index'])->name('pengguna');
Route::get('/deleteprofilpel/{id}', [UserController::class, 'destroy_pelanggan']);
Route::post('/editprofilpadm_process', [UserController::class, 'editprofilpadm_process']);
Route::get('/detailprofilpelanggan/{id}', [UserController::class, 'detail_profil_pelanggan']);
Route::get('/katalogadmin', [KatalogController::class,'index'])->name('katalogadmin');
Route::get('/katalogadmin/{id}', [KatalogController::class, 'detaildproduk']);
Route::post('/addproduct', [KatalogController::class, 'addproduct'])->name('addproduct');
Route::get('/tambahproduk', [KatalogController::class, 'create'])->name('tambahproduk');
Route::get('/kelolawarna', [KatalogController::class, 'index_warna'])->name('kelolawarna');
Route::post('/addwarna', [KatalogController::class, 'insert_warna'])->name('addwarna');
Route::get('/deletewarna/{id}', [KatalogController::class, 'destroy_warna']);
Route::get('/kelolabahan', [KatalogController::class, 'index_bahan'])->name('kelolabahan');
Route::post('/addbahan', [KatalogController::class, 'insert_bahan'])->name('addbahan');
Route::get('/deletebahan/{id}', [KatalogController::class, 'destroy_bahan']);
Route::get('/deleteproduk/{id}', [KatalogController::class, 'destroy_produk']);
Route::get('/editproduk/{id}', [KatalogController::class, 'edit_produk']);
Route::post('/editproduk_process', [KatalogController::class, 'editproduk_process']);
Route::get('/editprofilpeladmin/{id}', [UserController::class, 'edit_profil_pel_admin']);
Route::get('/daftarpesananadm', [PesananController::class,'index_pesananadm'])->name('pesananadmin');
Route::post('insert_pesananadm', [PesananController::class, 'insert_pesanan_admin'])->name('insert_pesanan_admin');
Route::get('/detailpesananadm/{id}', [PesananController::class, 'detail_pesanan_admin']);
Route::get('/detailpesananbypel/{id}', [PesananController::class, 'detail_pesanan_adminbypel']);
Route::get('/batalkanpesananadm/{id}', [PesananController::class, 'batal_pesanan_admin']);
Route::get('/batalkanpesananpel/{id}', [PesananController::class, 'batal_pesanan_pelanggan']);
Route::get('/selesaikanpesananadm/{id}', [PesananController::class, 'selesai_pesanan_admin']);
Route::get('/selesaikanpesananpelanggan/{id}', [PesananController::class, 'selesai_pesanan_pelanggan']);
Route::post('/updateStatusAdmin', [PesananController::class, 'update_status_admin']);
Route::post('/updateStatusPel', [PesananController::class, 'update_status_pelanggan']);
Route::get('/keranjangadmin/{id}', [KeranjangController::class,'isi_keranjang_admin'])->name('keranjangadmin');
Route::get('generate-invoice-adm/{id}', [PesananController::class, 'generateInvoicePdfAdm'])->name('generate.invoice.adm');
Route::get('generate-invoice-pel/{id}', [PesananController::class, 'generateInvoicePdfPel'])->name('generate.invoice.pel');
Route::get('/laporanpenjualan', [HasilPenjualanController::class, 'index_penjualan'])->middleware(['auth'])->name('laporanpenjualan');
Route::post('/addBuktiBayarPel', [PesananController::class, 'insert_bukti_bayar_pel']);
Route::get('generate-laporan', [HasilPenjualanController::class, 'generateLaporanPdf'])->name('generate-laporan');

// Pelanggan
Route::get('/dashboard', [DashboardController::class,'dashboard'])->middleware(['auth'])->name('dashboard');
Route::get('/katalogproduk', [KatalogController::class,'index_propel'])->name('katalogproduk');
Route::get('/katalogproduk/{id}', [KatalogController::class,'detailprodukpel']);
Route::get('/profilpelanggan/{id}', [AuthController::class, 'profil_pelanggan'])->name('profilpelanggan');
Route::get('/tambahprofilpelanggan/{id}', [AuthController::class, 'tambah_profil_pelanggan']);
Route::get('get-kota', [AuthController::class, 'get_kota'])->name('get.kota');
Route::get('get-kota-edit', [AuthController::class, 'get_kota_edit'])->name('get.kota.edit');
Route::get('get-kecamatan-edit', [AuthController::class, 'get_kecamatan_edit'])->name('get.kecamatan.edit');
Route::get('get-kecamatan', [AuthController::class, 'get_kecamatan'])->name('get.kecamatan');
Route::post('/addprofilpel', [AuthController::class, 'add_profil'])->name('addprofil');
Route::get('/editprofilpelanggan/{id}', [AuthController::class, 'edit_profil_pelanggan']);
Route::post('/editprofilp_process', [AuthController::class, 'editprofilp_process']);
Route::post('/tambahkeranjang', [KeranjangController::class, 'insert_keranjang']);
Route::get('/keranjang/{id}', [KeranjangController::class,'isi_keranjang'])->name('keranjangpelanggan');
Route::get('/deletekeranjang', [KeranjangController::class, 'destroy_keranjang']);
Route::post('check-ongkir', [KeranjangController::class, 'checkOngkir'])->name('check-ongkir');
Route::post('insert_pesanan', [PesananController::class, 'insert_pesanan'])->name('insert_pesanan');
Route::get('/daftarpesanan', [PesananController::class,'index_pesanan'])->name('daftar_pesanan');
Route::get('/detailpesananpel/{id}', [PesananController::class, 'detail_pesanan_pelanggan']);
Route::post('/addBuktiBayarAdm', [PesananController::class, 'insert_bukti_bayar_adm']);
Route::get('/selesaikanpesananpel/{id}', [PesananController::class, 'selesai_pesanan_pelangganbypel']);
Route::get('getCartQuantity', [KeranjangController::class,'cart_quantity'])->name('get.cartQuantity');
Route::post('insert_alamat', [KeranjangController::class, 'insert_alamat_baru'])->name('insert_alamat');
Route::get('/batalkanpesananbypel/{id}', [PesananController::class, 'batal_pesanan_pelanggan']);

require __DIR__.'/auth.php';

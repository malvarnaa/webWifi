<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\CalonPelangganController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\PermintaanController;
use App\Http\Controllers\pelanggan\BantuanController;
use App\Http\Controllers\pelanggan\PelangganController;
use App\Http\Controllers\pelanggan\StatusLayananController;
use App\Http\Controllers\PromoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/landing-page', function () {
//     return view('calon.landing');
// });

Route::get('/', function(){
    return redirect('/landing-page');
});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');

});

Route::middleware(['auth', 'userAkses:admin'])->group(function () {
    Route::get('/dashboard/admin', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');

    // Provinsi
    Route::get('/provinsi', [AlamatController::class, 'prov'])->name('prov.index');
    Route::post('/provinsi', [AlamatController::class, 'provStore'])->name('prov.store');
    Route::put('/provinsi/update/{id}', [AlamatController::class, 'provUpdate'])->name('prov.update');
    Route::delete('/provinsi/delete/{prov}', [AlamatController::class, 'destroy'])->name('prov.destroy');
    Route::get('/cari-provinsi', [AlamatController::class, 'cariProv'])->name('cari.prov');


    // Kabupaten
    Route::get('/kabupaten', [AlamatController::class, 'kab'])->name('kab.index');
    Route::post('/kabupaten', [AlamatController::class, 'kabStore'])->name('kab.store');
    Route::put('/kabupaten/update/{id}', [AlamatController::class, 'kabUpdate'])->name('kab.update');
    Route::delete('/kabupaten/delete/{kab}', [AlamatController::class, 'kabDestroy'])->name('kab.destroy');
    Route::get('/cari-kabupaten', [AlamatController::class, 'cariKab'])->name('cari.kab');


    // Kecamatan
    Route::get('/kecamatan', [AlamatController::class, 'kec'])->name('kec.index');
    Route::get('/get-kabupaten/{prov_id}', [AlamatController::class, 'getKabupaten']);
    Route::get('/get-kecamatan/{kab_id}', [AlamatController::class, 'getKecamatan']);
    Route::get('/get-kelurahan-desa/{kec_id}', [AlamatController::class, 'getDesa']);
    Route::post('/kecamatan', [AlamatController::class, 'kecStore'])->name('kec.store');
    Route::put('/kecamatan/update/{id}', [AlamatController::class, 'kecUpdate'])->name('kec.update');
    Route::delete('/kecamatan/delete/{kec}', [AlamatController::class, 'kecDestroy'])->name('kec.destroy');
    Route::get('/cari-kecamatan', [AlamatController::class, 'cariKec'])->name('cari.kec');


    //desa
    Route::get('/kelurahan-desa', [AlamatController::class, 'desa'])->name('desa.index');
    Route::post('/kelurahan-desa', [AlamatController::class, 'desaStore'])->name('desa.store');
    Route::get('/cari-desa', [AlamatController::class, 'cariDesa'])->name('cari.desa');


    // Paket Wifi
    Route::get('/paket-wifi', [PaketController::class, 'index'])->name('paket.index');
    Route::post('/paket-wifi', [PaketController::class, 'store'])->name('paket.store');
    Route::get('/paket-wifi/edit/{paket}', [PaketController::class, 'edit'])->name('paket.edit');
    Route::put('/paket-wifi/update/{paket}', [PaketController::class, 'update'])->name('paket.update');
    Route::get('/paket-wifi/{paket}', [PaketController::class, 'show'])->name('paket.show');
    Route::delete('/paket/{paket}', [PaketController::class, 'destroy'])->name('paket.destroy');
    Route::get('/cari-paket', [PaketController::class, 'cari'])->name('cari.paket');


    // Review Pesanan
    Route::get('/review-pesanan', [ReviewController::class, 'reviewPesanan'])->name('review.pesanan');
    Route::get('/review-pesanan/{id}', [ReviewController::class, 'showPesanan'])->name('pesanan.show');

    Route::get('/cari-pesanan', [PesananController::class, 'cari'])->name('pesanan.cari');

    // riwayat pesanan
    Route::get('/riwayat/diterima', [PesananController::class, 'riwayatDiterima'])->name('riwayat.diterima');
    Route::get('/riwayat/ditolak', [PesananController::class, 'riwayatDitolak'])->name('riwayat.ditolak');

    Route::get('/riwayat-diterima/cari', [ReviewController::class, 'cariDiterima'])->name('cari.diterima');
    Route::get('/riwayat-ditolak/cari', [ReviewController::class, 'cariDitolak'])->name('cari.ditolak');


    // halaman terima tolak pesanan
    Route::post('/pesanan/{id}/terima', [PesananController::class, 'terimaPesanan'])->name('pesanan.terima');
    Route::post('/pesanan/{id}/tolak', [PesananController::class, 'tolakPesanan'])->name('pesanan.tolak');

    //tambah excel
    Route::post('/provinsi/import', [AlamatController::class, 'importProvinsi'])->name('provinsi.import');
    Route::post('/kabupaten/import', [AlamatController::class, 'importKabupaten'])->name('kabupaten.import');
    Route::post('/kecamatan/import', [AlamatController::class, 'importKecamatan'])->name('kecamatan.import');


    // konfirmasi permintaan bantuan
    Route::get('/permintaan', [PermintaanController::class, 'index'])->name('admin.permintaan.index');
    Route::get('/permintaan/{id}', [PermintaanController::class, 'show'])->name('admin.permintaan.show');
    Route::post('/permintaan/{id}/status', [PermintaanController::class, 'ubahStatus'])->name('admin.permintaan.status');

    Route::get('/daftar-pelanggan', [PelangganController::class, 'data_pelanggan'])->name('daftar.pelanggan');    
    Route::get('/pelanggan/aktif/{id}', [PelangganController::class, 'detail_pelanggan_aktif'])->name('pelanggan.detail');
    // Route::get('/pelanggan/export', [PelangganController::class, 'exportPelanggan'])->name('pelanggan.export');
    Route::get('/ekspor-pelanggan-aktif/pdf', [PelangganController::class, 'exportPDFPelanggan'])->name('pelanggan.ekspor.pdf');
    Route::get('/pelanggan/pdf', [PelangganController::class, 'PdfPelanggan'])->name('pdf.pelanggan');

    //Promo
    Route::get('/promo-paket', [PromoController::class, 'index'])->name('promo.paket');
    Route::post('/promo-paket', [PromoController::class, 'store'])->name('promo.store');
    Route::get('/promo-paket/{promo}', [PromoController::class, 'show'])->name('promo.show');
    Route::get('/promo-paket/edit/{promo}', [PromoController::class, 'edit'])->name('promo.edit');
    Route::put('/promo/{id}', [PromoController::class, 'update'])->name('promo.update');
    Route::delete('/promo/{promo}', [PromoController::class, 'destroy'])->name('promo.destroy');
    Route::get('/cari-promo', [PromoController::class, 'cari'])->name('cari.promo');



});


Route::middleware(['auth', 'userAkses:pelanggan'])->group(function(){
    Route::get('/pelanggan/dashboard', [PelangganController::class, 'pelangganDashboard'])->name('pelanggan.dashboard');

    // status layanan
    Route::get('/pelanggan/status_layanan', [StatusLayananController::class, 'statusLayanan'])->name('statusLayanan.index');

    // bantuan layanan pelanggan
    Route::get('/pelanggan/bantuan', [BantuanController::class, 'index'])->name('bantuan.index');
    Route::post('/pelanggan/bantuan/kirim-pesan', [BantuanController::class, 'kirimPesan'])->name('bantuan.kirimPesan');
    Route::post('/pelanggan/bantuan/permintaan-service', [BantuanController::class, 'permintaanService'])->name('bantuan.permintaanService');
});

// Route::middleware(['auth', 'userAkses:calon'])->group(function(){
//     Route::get('/pelanggan/dashboard', [PelangganController::class, 'pelangganDashboard'])->name('pelanggan.dashboard');

// });

// Calon Pelanggan
Route::get('/landing-page', [CalonPelangganController::class, 'index'])->name('landing.page');
Route::get('/register', [CalonPelangganController::class, 'register'])->name('calon.register');
Route::post('/register', [CalonPelangganController::class, 'registerStore'])->name('register.store');
Route::get('/get-kabupaten/{prov_id}', [CalonPelangganController::class, 'getKabupaten']);
Route::get('/get-kecamatan/{kab_id}', [CalonPelangganController::class, 'getKecamatan']);
Route::get('/get-desa/{kec_id}', [CalonPelangganController::class, 'getDesa']);







// Route dashboard admin untuk melihat data modul
Route::get('/admin/module', [ModuleController::class, 'index'])->name('module.index'); // Menampilkan data modul

// Route untuk menyimpan data modul baru
Route::post('/admin/module', [ModuleController::class, 'store'])->name('module.store'); // Menyimpan data modul

Route::get('/module/edit/{id}', [ModuleController::class, 'edit'])->name('module.edit');
Route::get('/module/{id}', [ModuleController::class, 'show'])->name('module.show');
Route::get('/module/{id}/edit', [ModuleController::class, 'edit'])->name('module.edit');
Route::resource('module', ModuleController::class);


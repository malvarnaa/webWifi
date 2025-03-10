<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\CalonPelangganController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ReviewController;
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
    return redirect('/login');
});

Route::get('/logout', function () {
    return redirect('/landing-page');
});

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

    // Kabupaten
    Route::get('/kabupaten', [AlamatController::class, 'kab'])->name('kab.index');
    Route::post('/kabupaten', [AlamatController::class, 'kabStore'])->name('kab.store');
    Route::put('/kabupaten/update/{id}', [AlamatController::class, 'kabUpdate'])->name('kab.update');
    Route::delete('/kabupaten/delete/{kab}', [AlamatController::class, 'kabDestroy'])->name('kab.destroy');

    // Kecamatan
    Route::get('/kecamatan', [AlamatController::class, 'kec'])->name('kec.index');
    Route::post('/kecamatan', [AlamatController::class, 'kecStore'])->name('kec.store');
    Route::put('/kecamatan/update/{id}', [AlamatController::class, 'kecUpdate'])->name('kec.update');
    Route::delete('/kecamatan/delete/{kec}', [AlamatController::class, 'kecDestroy'])->name('kec.destroy');

    // Paket Wifi
    Route::get('/paket-wifi', [PaketController::class, 'index'])->name('paket.index');
    Route::post('/paket-wifi', [PaketController::class, 'store'])->name('paket.store');
    Route::get('/paket-wifi/edit/{paket}', [PaketController::class, 'edit'])->name('paket.edit');
    Route::put('/paket-wifi/update/{paket}', [PaketController::class, 'update'])->name('paket.update');
    Route::get('/paket-wifi/{paket}', [PaketController::class, 'show'])->name('paket.show');
    Route::delete('/paket/{paket}', [PaketController::class, 'destroy'])->name('paket.destroy');

    // Review Pesanan
    Route::get('/review-pesanan', [ReviewController::class, 'reviewPesanan'])->name('review.pesanan');
    Route::get('/review-pesanan/{id}', [ReviewController::class, 'showPesanan'])->name('pesanan.show');

    // riwayat pesanan
    Route::get('/riwayat/diterima', [PesananController::class, 'riwayatDiterima'])->name('riwayat.diterima');
    Route::get('/riwayat/ditolak', [PesananController::class, 'riwayatDitolak'])->name('riwayat.ditolak');

    // halaman terima tolak pesanan
    Route::post('/pesanan/{id}/terima', [PesananController::class, 'terimaPesanan'])->name('pesanan.terima');
    Route::post('/pesanan/{id}/tolak', [PesananController::class, 'tolakPesanan'])->name('pesanan.tolak');
    
});


Route::middleware(['auth', 'userAkses:pelanggan'])->group(function(){
    Route::get('/dashboard/pelanggan', [PelangganController::class, 'pelangganDashboard'])->name('pelanggan.dashboard');

});

Route::middleware(['auth', 'userAkses:calon'])->group(function(){
    Route::get('/dashboard/pelanggan', [PelangganController::class, 'pelangganDashboard'])->name('pelanggan.dashboard');

});

// Calon Pelanggan
Route::get('/landing-page', [CalonPelangganController::class, 'index'])->name('landing.page');
Route::get('/register', [CalonPelangganController::class, 'register'])->name('calon.register');
Route::post('/register', [CalonPelangganController::class, 'registerStore'])->name('register.store');
Route::get('/get-kabupaten/{prov_id}', [CalonPelangganController::class, 'getKabupaten']);
Route::get('/get-kecamatan/{kab_id}', [CalonPelangganController::class, 'getKecamatan']);



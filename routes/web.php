<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PelangganController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function(){
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');

Route::get('/dashboard/admin', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');

Route::get('/dashboard/pelanggan', [PelangganController::class, 'pelangganDashboard'])->name('pelanggan.dashboard');

Route::get('/paket-wifi', [PaketController::class, 'index'])->name('paket.index');
Route::post('/paket-wifi', [PaketController::class, 'store'])->name('paket.store');
Route::get('/paket-wifi/edit/{paket}', [PaketController::class, 'edit'])->name('paket.edit');
Route::put('/paket-wifi/update/{paket}', [PaketController::class, 'update'])->name('paket.update');
Route::get('/paket-wifi/{paket}', [PaketController::class, 'show'])->name('paket.show');
Route::delete('/paket/{paket}', [PaketController::class, 'destroy'])->name('paket.destroy');

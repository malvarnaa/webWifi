<?php

namespace App\Http\Controllers\pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Register;
use App\Exports\PelangganExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PelangganController extends Controller
{
    public function pelangganDashboard(){
        return view('user_active.dashboard');
    }

    public function data_pelanggan() {
        $pelanggans = Register::where('status', 'diterima')->get();

        foreach ($pelanggans as $pelanggan) {
            if ($pelanggan->jatuh_tempo && now()->gt(\Carbon\Carbon::parse($pelanggan->jatuh_tempo))) {
                $pelanggan->status_kepelangganan = 'non-aktif';
            } else {
                $pelanggan->status_kepelangganan = 'aktif';
            }
        }

        return view('pelanggan.daftar_pelanggan', compact('pelanggans'));
    }

    public function detail_pelanggan_aktif($id)
    {
        $register = Register::with(['prov', 'kab', 'kec', 'desa', 'paket', 'user'])->findOrFail($id);

        return view('pelanggan.detail_pelanggan_aktif', compact('register'));
    }

}

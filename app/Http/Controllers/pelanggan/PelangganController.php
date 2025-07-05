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

    public function exportPDFPelanggan(Request $request)
    {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $data = Register::where('status', 'diterima')
                        ->whereBetween('tanggal_diterima', [$startDate, $endDate])
                        ->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('warning', 'Data tidak ditemukan untuk tanggal yang dipilih.');
        }

        $pdf = Pdf::loadView('pdf.pelanggan_export', compact('data', 'startDate', 'endDate'))
                ->setPaper('A4', 'landscape');

        return $pdf->download('data_pelanggan_' . now()->format('Ymd_His') . '.pdf');
    }


}

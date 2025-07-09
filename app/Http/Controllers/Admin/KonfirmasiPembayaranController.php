<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Register;
use App\Models\TagihanBulanan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class KonfirmasiPembayaranController extends Controller
{
    public function index()
    {
        // Ambil semua tagihan yang statusnya 'tertunda', artinya sudah upload bukti dan menunggu konfirmasi
        $data = TagihanBulanan::with('register')
            ->where('status', 'tertunda')
            ->orderByDesc('jatuh_tempo')
            ->get();

        return view('admin.konfirmasi_pembayaran.utama', compact('data'));
    }


    public function konfirmasi($id)
    {
        $tagihan = TagihanBulanan::findOrFail($id);
        $register = $tagihan->register;

        // Update tagihan ini menjadi lunas
        $tagihan->update([
            'status' => 'lunas',
        ]);

        // Pastikan pelanggan tetap aktif
        if ($register->status_kepelangganan !== 'aktif') {
            $register->update([
                'status_kepelangganan' => 'aktif',
            ]);
        }

        // Hitung bulan dan jatuh tempo baru
        $jatuhTempoLama = Carbon::parse($tagihan->jatuh_tempo);
        $bulanSelanjutnya = $jatuhTempoLama->copy()->addMonth();

        $jatuhTempoBaru = $jatuhTempoLama->day <= 5
            ? $bulanSelanjutnya->copy()->day(5)
            : $bulanSelanjutnya->copy()->day(20);

        // Cek apakah tagihan bulan depan sudah ada
        $sudahAda = TagihanBulanan::where('register_id', $register->id)
            ->where('bulan', $bulanSelanjutnya->translatedFormat('F Y'))
            ->exists();

        if (!$sudahAda) {
            TagihanBulanan::create([
                'register_id' => $register->id,
                'bulan' => $bulanSelanjutnya->translatedFormat('F Y'),
                'jumlah' => $register->total_harga,
                'status' => 'belum_lunas',
                'jatuh_tempo' => $jatuhTempoBaru,
            ]);
        }


        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi dan tagihan bulan berikutnya telah dibuat.');
    }

}

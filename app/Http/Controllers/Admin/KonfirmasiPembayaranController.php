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

        // Update status tagihan ini menjadi lunas
        $tagihan->update([
            'status' => 'lunas',
        ]);

        // Aktifkan kembali pelanggan jika sebelumnya tidak aktif
        if ($register->status_kepelangganan !== 'aktif') {
            $register->update([
                'status_kepelangganan' => 'aktif',
            ]);
        }

        // Ambil tanggal jatuh tempo awal (misalnya 5 atau 20)
        $tanggalAwal = Carbon::parse($tagihan->jatuh_tempo)->day;

        // Ambil bulan dari field "bulan" untuk menentukan tagihan bulan berikutnya
        $bulanLama = Carbon::createFromFormat('F Y', $tagihan->bulan);
        $bulanBerikutnya = $bulanLama->copy()->addMonth(); // misalnya: August 2025

        // Jatuh tempo selalu satu bulan setelah bulan tagihan
        $jatuhTempoBulan = $bulanBerikutnya->copy()->addMonth(); // misalnya: September 2025

        // Buat tanggal jatuh tempo (5 atau 20 di bulan jatuh tempo)
        $jatuhTempoBaru = $jatuhTempoBulan->copy()->startOfMonth()->addDays($tanggalAwal - 1);

        // Cegah overflow tanggal (misal 31 Februari)
        if ($jatuhTempoBaru->month !== $jatuhTempoBulan->month) {
            $jatuhTempoBaru = $jatuhTempoBulan->copy()->endOfMonth();
        }

        // Cek apakah tagihan untuk bulan berikutnya sudah ada
        $sudahAda = TagihanBulanan::where('register_id', $register->id)
            ->where('bulan', $bulanBerikutnya->translatedFormat('F Y'))
            ->exists();

        // Buat tagihan baru jika belum ada
        if (!$sudahAda) {
            TagihanBulanan::create([
                'register_id' => $register->id,
                'bulan' => $bulanBerikutnya->translatedFormat('F Y'), // contoh: August 2025
                'jumlah' => $register->total_harga,
                'status' => 'belum_lunas',
                'jatuh_tempo' => $jatuhTempoBaru->toDateString(), // contoh: 2025-09-05
            ]);
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi dan tagihan bulan berikutnya telah dibuat.');
    }



}

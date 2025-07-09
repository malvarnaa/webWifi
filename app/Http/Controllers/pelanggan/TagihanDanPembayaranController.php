<?php

namespace App\Http\Controllers\pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Register;
use App\Models\TagihanBulanan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TagihanDanPembayaranController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'pelanggan') {
            abort(403);
        }

        $register = Register::where('email', $user->email)
            ->where('status_kepelangganan', 'aktif')
            ->first();

        if (!$register) {
            return view('user_active.tagihan_dan_pembayaran.utama', [
                'tagihan' => [],
                'totalTagihan' => 0,
                'jatuhTempo' => null,
                'statusPembayaran' => 'Tidak Aktif',
                'tanggalBukaPembayaran' => null,
            ]);
        }

        // Ambil semua tagihan user
        $tagihanList = TagihanBulanan::where('register_id', $register->id)
            ->orderBy('jatuh_tempo', 'desc')
            ->get();

        // Cek apakah tagihan terakhir sudah lunas dan kapan
        $tagihanTerakhirLunas = $tagihanList
            ->where('status', 'lunas')
            ->sortByDesc('updated_at')
            ->first();

            if ($tagihanTerakhirLunas) {
                $tanggalBukaPembayaran = Carbon::parse($tagihanTerakhirLunas->updated_at)->addDays(10);
                $bolehTagihBaru = now()->greaterThanOrEqualTo($tanggalBukaPembayaran);

                if (!$bolehTagihBaru) {
                    return view('user_active.tagihan_dan_pembayaran.utama', [
                        'tagihan' => [],
                        'totalTagihan' => null,
                        'jatuhTempo' => null,
                        'statusPembayaran' => 'Lunas',
                        'tanggalBukaPembayaran' => $tanggalBukaPembayaran->translatedFormat('d F Y'),
                    ]);
                }
            }


        // Ambil tagihan yang belum lunas (limit 1–2 terbaru)
        $tagihanBelumLunas = $tagihanList->where('status', '!=', 'lunas')->take(2);

        // Ambil tagihan aktif pertama
        $tagihanAktif = $tagihanBelumLunas->first();

        $totalTagihan = $tagihanAktif ? $register->total_harga : null;
        $jatuhTempo = $tagihanAktif ? Carbon::parse($tagihanAktif->jatuh_tempo)->format('Y-m-d') : null;
        $statusPembayaran = $tagihanAktif ? $tagihanAktif->status : 'lunas';

        return view('user_active.tagihan_dan_pembayaran.utama', [
            'tagihan' => $tagihanBelumLunas,
            'totalTagihan' => $totalTagihan,
            'jatuhTempo' => $jatuhTempo,
            'statusPembayaran' => $statusPembayaran,
            'tanggalBukaPembayaran' => null, // karena sudah boleh tagih
        ]);
    }


    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $tagihan = TagihanBulanan::findOrFail($id);
        $register = Register::where('email', auth()->user()->email)
            ->where('status_kepelangganan', 'aktif')
            ->first();

        if (!$register || $tagihan->register_id !== $register->id) {
            abort(403);
        }

        $file = $request->file('bukti');
        $path = $file->store('bukti_transfer', 'public');

        $tagihan->update([
            'bukti_transfer' => $path,
            'status' => 'tertunda',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.');
    }

    // Fungsi ini bisa dipanggil oleh admin saat mengkonfirmasi pembayaran
    // public function konfirmasiPembayaran($id)
    // {
    //     $tagihan = TagihanBulanan::findOrFail($id);

    //     if ($tagihan->status !== 'tertunda') {
    //         return back()->with('error', 'Pembayaran belum diupload atau sudah dikonfirmasi.');
    //     }

    //     $tagihan->status = 'lunas';
    //     $tagihan->tanggal_diterima = now();
    //     $tagihan->save();

    //     // Tentukan jatuh tempo berikutnya berdasarkan tanggal_diterima pertama
    //     $register = $tagihan->register;

    //     // Cek apakah ini pembayaran pertama (belum ada tagihan lunas sebelumnya)
    //     $pembayaranPertama = TagihanBulanan::where('register_id', $register->id)
    //         ->where('status', 'lunas')
    //         ->orderBy('tanggal_diterima')
    //         ->first();

    //     $tanggalBerikutnya = null;

    //     if (!$pembayaranPertama || $pembayaranPertama->id === $tagihan->id) {
    //         $tanggalKonfirmasi = Carbon::parse($tagihan->tanggal_diterima);
    //         $tanggalBerikutnya = $tanggalKonfirmasi->day <= 15
    //             ? $tanggalKonfirmasi->copy()->addMonth()->day(5)
    //             : $tanggalKonfirmasi->copy()->addMonth()->day(20);
    //     } else {
    //         $tanggalKonfirmasi = Carbon::parse($tagihan->tanggal_diterima);
    //         $tanggalBerikutnya = $tanggalKonfirmasi->copy()->addMonth()->day(Carbon::parse($pembayaranPertama->jatuh_tempo)->day);
    //     }

    //     // Buat tagihan baru untuk bulan berikutnya
    //     TagihanBulanan::create([
    //         'register_id' => $register->id,
    //         'bulan' => $tanggalBerikutnya->format('F Y'),
    //         'jumlah' => $register->total_harga,
    //         'status' => 'belum_lunas',
    //         'jatuh_tempo' => $tanggalBerikutnya,
    //     ]);

    //     return back()->with('success', 'Pembayaran dikonfirmasi dan tagihan bulan berikutnya berhasil dibuat.');
    // }

    // Jalankan ini otomatis (via schedule) untuk nonaktifkan yang melewati jatuh tempo
    public function cekStatusKepelangganan()
    {
        $registers = Register::where('status_kepelangganan', 'aktif')->get();

        foreach ($registers as $reg) {
            $tagihan = $reg->tagihan()->where('status', 'belum_lunas')
                ->where('jatuh_tempo', '<', now())
                ->first();

            if ($tagihan) {
                $reg->update(['status_kepelangganan' => 'non-aktif']);
            }
        }
    }
}

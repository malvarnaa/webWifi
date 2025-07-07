<?php
namespace App\Http\Controllers\pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Register;
use Illuminate\Support\Facades\Auth;

class TagihanDanPembayaranController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // Cek role
        if ($user->role !== 'pelanggan') {
            abort(403);
        }

        // Ambil data register berdasarkan email pelanggan
        $register = Register::where('email', $user->email)->first();

        if (!$register) {
            return view('user_active.tagihan_dan_pembayaran.utama', [
                'tagihan' => [],
                'totalTagihan' => 0,
                'jatuhTempo' => null,
                'statusPembayaran' => 'Belum Tersedia',
            ]);
        }

        // Tentukan status pembayaran
        $statusPembayaran = $register->status_kepelangganan === 'aktif' ? 'Lunas' : 'Belum Lunas';

        // Atur nilai total tagihan
        $totalTagihan = $statusPembayaran === 'Lunas' ? null : $register->total_harga;

        return view('user_active.tagihan_dan_pembayaran.utama', [
            'tagihan' => $statusPembayaran === 'Lunas' ? [] : [
                (object)[
                    'id' => $register->id,
                    'bulan' => now()->format('F Y'),
                    'jumlah' => $register->total_harga,
                    'status' => $statusPembayaran,
                    'bukti' => null,
                ],
            ],
            'totalTagihan' => $totalTagihan,
            'jatuhTempo' => $register->jatuh_tempo,
            'statusPembayaran' => $statusPembayaran,
        ]);
    }


    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $register = Register::findOrFail($id);

        // Cek kepemilikan berdasarkan email
        if ($register->email !== auth()->user()->email) {
            abort(403);
        }

        $file = $request->file('bukti');
        $path = $file->store('bukti_transfer', 'public');

        $register->update(['bukti_transfer' => $path]);

        return back()->with('success', 'Bukti transfer berhasil diupload.');
    }
}

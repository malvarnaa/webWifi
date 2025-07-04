<?php

namespace App\Http\Controllers\pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermintaanBantuan;
use App\Models\User;
use App\Notifications\NotifikasiPermintaanBantuan;

class BantuanController extends Controller
{
    public function index()
    {
        $riwayat = PermintaanBantuan::where('user_id', auth()->id())->latest()->get();
        return view('user_active.bantuan_layanan_pelanggan.utama', compact('riwayat'));
    }

    public function kirimPesan(Request $request)
    {
        $request->validate([
            'subjek' => 'required|string',
            'pesan' => 'required|string',
        ]);

        $permintaan = PermintaanBantuan::create([
            'user_id' => auth()->id(),
            'jenis' => 'pesan',
            'subjek' => $request->subjek,
            'deskripsi' => $request->pesan,
        ]);

        // Kirim notifikasi ke semua admin
        User::where('role', 'admin')->get()
            ->each(fn ($admin) => $admin->notify(new NotifikasiPermintaanBantuan($permintaan)));

        return back()->with('success', 'Pesan Anda telah dikirim.');
    }

    public function permintaanService(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
            'deskripsi' => 'required|string',
            'waktu' => 'nullable|date',
        ]);

        $permintaan = PermintaanBantuan::create([
            'user_id' => auth()->id(),
            'jenis' => 'service',
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'waktu' => $request->waktu,
        ]);

        // Kirim notifikasi ke semua admin
        User::where('role', 'admin')->get()
            ->each(fn ($admin) => $admin->notify(new NotifikasiPermintaanBantuan($permintaan)));

        return back()->with('success', 'Permintaan teknisi telah dikirim.');
    }
}

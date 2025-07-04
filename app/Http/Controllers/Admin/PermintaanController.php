<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermintaanBantuan;
use Illuminate\Http\Request;

class PermintaanController extends Controller
{
    public function index()
    {
        $permintaan = PermintaanBantuan::with('user')->latest()->get();
        return view('admin.permintaan.index', compact('permintaan'));
    }

    public function show($id)
    {
        $data = PermintaanBantuan::with('user')->findOrFail($id);

        // Tandai notifikasi sebagai dibaca (jika dari notifikasi)
        auth()->user()->unreadNotifications
        ->where('data.id', $id)
        ->each->markAsRead();


        return view('admin.permintaan.detail', compact('data'));
    }

    public function ubahStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai'
        ]);

        $permintaan = PermintaanBantuan::findOrFail($id);
        $permintaan->status = $request->status;
        $permintaan->save();

        return back()->with('success', 'Status berhasil diubah.');
    }
}

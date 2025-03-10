<?php

namespace App\Http\Controllers;

use App\Models\Register;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function riwayatDiterima()
    {
        $pesanan = Register::where('status', 'diterima')->get();
        return view('review.riwayatDiterima', compact('pesanan'));
    }
    

    public function riwayatDitolak()
    {
        $pesanan = Register::where('status', 'ditolak')->get();
        return view('review.riwayatDitolak', compact('pesanan'));
    }
    

    public function terimaPesanan($id) {
        $pesanan = Register::findOrFail($id);
        $pesanan->status = 'diterima'; // Set status manual
        $pesanan->save(); // Simpan perubahan
        
        return redirect()->route('review.pesanan')->with('success', 'Pesanan berhasil diterima.');
    }
    
    public function tolakPesanan($id) {
        $pesanan = Register::findOrFail($id);
        $pesanan->status = 'ditolak'; // Set status manual
        $pesanan->save(); // Simpan perubahan
        
        return redirect()->route('review.pesanan')->with('success', 'Pesanan berhasil ditolak.');
    }
    
}

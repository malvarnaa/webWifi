<?php

namespace App\Http\Controllers;

use App\Models\Register;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function reviewPesanan(){
        $register = Register::with(['prov', 'kab', 'kec'])
            ->where('status', 'pending') // Hanya ambil pesanan yang masih pending
            ->get();
        return view('review.reviewPesanan', compact('register'));
    }

    public function showPesanan($id){
        $register = Register::with(['prov', 'kab', 'kec'])->findOrFail($id);
        return view('review.detailPesanan', compact('register'));
    }

    public function cariDiterima(Request $request)
{
    $query = Register::with('paket')->where('status', 'diterima');

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('nama_cust', 'like', "%{$search}%")
              ->orWhere('nik', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhereHas('paket', function ($q2) use ($search) {
                  $q2->where('nama_paket', 'like', "%{$search}%");
              });
        });
    }

    // Ambil hasil akhirnya di sini
    $pesanan = $query->latest()->get();

    return view('review.riwayatDiterima', compact('pesanan'));
}

    public function cariDitolak(Request $request)
    {
       
    $query = Register::with('paket')->where('status', 'ditolak');

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('nama_cust', 'like', "%{$search}%")
              ->orWhere('nik', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhereHas('paket', function ($q2) use ($search) {
                  $q2->where('nama_paket', 'like', "%{$search}%");
              });
        });
    }

    $pesanan = $query->latest()->get();
        return view('review.riwayatDitolak', compact('pesanan'));
    }

}
    
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
}

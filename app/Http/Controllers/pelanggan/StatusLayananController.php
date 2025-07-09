<?php

namespace App\Http\Controllers\pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Register;
use Illuminate\Support\Facades\Auth;

class StatusLayananController extends Controller
{
    public function index()
    {
        $register = Register::where('email', Auth::user()->email)->first();

        $tagihanTerbaru = $register?->tagihan()
            ->where('status', '!=', 'lunas')
            ->orderBy('jatuh_tempo', 'asc')
            ->first(); // hanya satu

        return view('user_active.status_layanan.utama', [
            'register' => $register,
            'tagihan' => $tagihanTerbaru,
        ]);
    }

}

<?php

namespace App\Http\Controllers\pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Register;
use Illuminate\Support\Facades\Auth;

class StatusLayananController extends Controller
{
    public function index()
    {
        // Ambil data register berdasarkan user yang sedang login
        $register = Register::where('email', Auth::user()->email)->first();
        // dd($register);

        return view('user_active.status_layanan.utama', compact('register'));
    }
}

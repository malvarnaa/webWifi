<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Register;
use App\Models\TagihanBulanan;
use Carbon\Carbon;

class TagihanBelumDibayarController extends Controller
{
    public function index()
    {
        $dataSemua = Register::where('status', 'diterima')
        ->whereHas('tagihan')
        ->with(['user', 'paket', 'tagihan' => fn($q) => $q->orderBy('jatuh_tempo')])
        ->get();

    $dataLunas = $dataSemua->filter(fn($item) =>
        $item->tagihan->count() > 0 && $item->tagihan->every(fn($t) => $t->status === 'lunas')
    );


        return view('admin.daftar_tagihan_pelanggan.utama', compact('dataSemua', 'dataLunas'));
    }

}

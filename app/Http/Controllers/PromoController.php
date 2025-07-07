<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index() {
        // Tidak perlu update status di database
        $promo = Promo::all();
        return view('promo.paket_promo', compact('promo'));
    }
    
    
    
    public function store(Request $request)
{
    // Validasi input tanpa field 'aktif'
    $validated = $request->validate([
        'nama_promo'         => 'required|string|max:255',
        'deskripsi'          => 'required|string',
        'jenis_promo'        => 'required|string',
        'diskon'             => 'nullable|numeric',
        'tipe_diskon'        => 'required|in:persen,nominal',
        'minimal_pembelian'  => 'required|numeric|min:0',
        'waktu_mulai'        => 'required|date',
        'waktu_berakhir'     => 'required|date|after:waktu_mulai',
        'batas_penggunaan'   => 'nullable|integer|min:1',
        'limit_per_user'     => 'nullable|integer|min:1',
        'paket_id'           => 'nullable|exists:paket,id',
        'gambar'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    // Tambahkan status aktif otomatis
    $waktuMulai = \Carbon\Carbon::parse($validated['waktu_mulai']);
    $waktuBerakhir = \Carbon\Carbon::parse($validated['waktu_berakhir']);
    $now = now();

    // Jika sekarang masih dalam rentang promo, set aktif = true
    $validated['aktif'] = $now->between($waktuMulai, $waktuBerakhir);

    // Handle upload gambar
    if ($request->hasFile('gambar')) {
        $path = $request->file('gambar')->store('promo', 'public');
        $validated['gambar'] = $path;
    }

    // Simpan ke database
    Promo::create($validated);

    return redirect()->route('promo.paket')->with('success', 'Promo berhasil ditambahkan.');
}


public function show(Promo $promo)
{
    return view('promo.detail_promo', compact('promo'));
}

public function update(Request $request, $id)
{
    $promo = Promo::findOrFail($id);

    $validated = $request->validate([
        'nama_promo' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'jenis_promo' => 'required|string',
        'diskon' => 'nullable|numeric',
        'tipe_diskon' => 'required|in:persen,nominal',
        'minimal_pembelian' => 'required|numeric|min:0',
        'waktu_mulai' => 'required|date',
        'waktu_berakhir' => 'required|date|after:waktu_mulai',
        'batas_penggunaan' => 'nullable|integer|min:1',
        'limit_per_user' => 'nullable|integer|min:1',
    ]);

    $promo->update($validated);

    return redirect()->route('promo.paket')->with('success', 'Promo berhasil diperbarui.');
}

public function edit($id) {
    $promo = Promo::findOrFail($id);
    return view('promo.edit_promo', compact('promo'));
}

public function cari(Request $request)
    {
        $query = Promo::query();
    
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_promo', 'like', "%$search%")
                  ->orWhere('jenis_promo', 'like', "%$search%");
        }
    
        $promo = $query->latest()->get(); 
    
        return view('promo.paket_promo', compact('promo'));
    }

public function destroy($id)
{
    $promo = Promo::findOrFail($id);


    $promo->delete();

    return redirect()->route('promo.paket')->with('success', 'Promo berhasil dihapus.');
}

}

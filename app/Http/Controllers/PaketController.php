<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paket = Paket::all();
        return view('paket_wifi.paket_wifi', compact('paket'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kecepatan' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
            'harga' => 'required|string|max:100',
        ]);

        Paket::create([
            'nama_paket' => $request->nama_paket,
            'kecepatan' => $request->kecepatan,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
        ]);

        return redirect()->back()->with('success','Paket Wifi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Paket $paket)
    {
        return view('paket_wifi.paket_wifi', compact('paket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paket $paket)
    {
        return view('paket_wifi.edit', compact('paket'));
    }    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Paket $paket)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kecepatan' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
            'harga' => 'required|string|max:255',
        ]);
        
        $paket->update([
            'nama_paket' => $request->nama_paket,
            'kecepatan' => $request->kecepatan,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
        ]);
    
        return redirect()->route('paket.index')->with('success', 'Data berhasil diperbarui.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paket $paket)
    {
        $paket->delete();
        return redirect()->route('paket.index')->with('success', 'Paket berhasil dihapus.');
    }
    
}

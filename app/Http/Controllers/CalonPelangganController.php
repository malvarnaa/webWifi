<?php

namespace App\Http\Controllers;

use App\Models\Kab;
use App\Models\Kec;
use App\Models\Paket;
use App\Models\Prov;
use App\Models\Register;
use Illuminate\Http\Request;

class CalonPelangganController extends Controller
{
    public function index() {
        $paket = Paket::all();
        return view('calon.landing', compact('paket'));
    }

    public function register(){
        $prov = Prov::all();
        $paket = Paket::all();
        return view('calon.register.register', compact('paket', 'prov'));
    }

    public function getKabupaten($prov_id)
    {
        $kabupaten = Kab::where('prov_id', $prov_id)->get();
        return response()->json($kabupaten);
    }

    public function getKecamatan($kab_id)
    {
        $kecamatan = Kec::where('kab_id', $kab_id)->get();
        return response()->json($kecamatan);
    }

    public function registerStore(Request $request) {
        $request->validate([
            'nama_cust' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'paket_id' => 'required|exists:pakets,id',
            'prov_id' => 'required|exists:provs,id',
            'kab_id' => 'required|exists:kabs,id',
            'kec_id' => 'required|exists:kecs,id',
            'alamat_lengkap' => 'required|string|max:255',
            'kebutuhan' => 'required|in:perumahan,apartemen,bisnis',
            'tanggal_pemasangan' => 'nullable|date',
            'total_harga' => 'required|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        Register::create([
            'nama_cust' => $request->nama_cust,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'paket_id' => $request->paket_id,
            'prov_id' => $request->prov_id,
            'kab_id' => $request->kab_id,
            'kec_id' => $request->kec_id,
            'alamat_lengkap' => $request->alamat_lengkap,
            'kebutuhan' => $request->kebutuhan,
            'tanggal_pemasangan' => $request->tanggal_pemasangan,
            'total_harga' => $request->total_harga,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,    
        ]);

        return redirect()->back()->with('success', 'Data berhasil dikirim!');
    }
}

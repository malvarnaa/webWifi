<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kab;
use App\Models\Kec;
use App\Models\Paket;
use App\Models\Prov;
use App\Models\Register;
use Carbon\Carbon;
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
    
    public function getDesa($kec_id)
    {
        $desa = Desa::where('kec_id', $kec_id)->get();
        return response()->json($desa);
    }

    public function registerStore(Request $request) {
        $request->validate([
            'nama_cust' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:255|unique:registers,nomor_hp',
            'email' => 'required|email|max:255|unique:registers,email',
            'nik' => 'required|string|max:255|unique:registers,nik',
            'foto_ktp' => 'required|file',
            'selfie_ktp' =>'required|file',
            'foto_rumah' => 'required|file',
            'paket_id' => 'required|exists:pakets,id',
            'prov_id' => 'required|exists:provs,id',
            'kab_id' => 'required|exists:kabs,id',
            'kec_id' => 'required|exists:kecs,id',
            'desa_id' => 'required|exists:desas,id',
            'alamat_lengkap' => 'required|string|max:255',
            'kebutuhan' => 'required|in:perumahan,apartemen,bisnis',
            'tanggal_pemasangan' => 'nullable|date',
            'total_harga' => 'required|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $path = $request->file('foto_ktp')->store('ktp', 'public');

        Register::create([
            'nama_cust' => $request->nama_cust,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'nik' => $request->nik,
            'foto_ktp' => $path,
            'paket_id' => $request->paket_id,
            'selfie_ktp' => $request->selfie_ktp,
            'foto_rumah' => $request->foto_rumah,
            'prov_id' => $request->prov_id,
            'kab_id' => $request->kab_id,
            'kec_id' => $request->kec_id,
            'desa_id' => $request->desa_id,
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

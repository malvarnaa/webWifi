<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kab;
use App\Models\Kec;
use App\Models\Paket;
use App\Models\Promo;
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

        $promos = Promo::select('id', 'nama_promo', 'diskon', 'tipe_diskon', 'minimal_pembelian', 'paket_id')
        ->where('waktu_mulai', '<=', now())
        ->where('waktu_berakhir', '>=', now())
        ->get();
    
    
        return view('calon.register.register', compact('paket', 'prov', 'promos'));
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

        $ktpPath = $request->file('foto_ktp')->store('ktp', 'public');
        $selfiePath = $request->file('selfie_ktp')->store('selfie', 'public');
        $rumahPath = $request->file('foto_rumah')->store('rumah', 'public');



        $paket = Paket::findOrFail($request->paket_id);
        $harga_asli = $paket->harga;
        $total_harga = $harga_asli;
        $promo_id = null;
        

        $promo = Promo::where('waktu_mulai', '<=', now())
            ->where('waktu_berakhir', '>=', now())
            ->where(function($query) use ($request) {
                $query->whereNull('paket_id')
                    ->orWhere('paket_id', $request->paket_id);
            })
            ->where('minimal_pembelian', '<=', $harga_asli)
            ->orderByDesc('diskon')
            ->first();

        if ($promo) {
            if ($promo->tipe_diskon === 'persen') {
                $potongan = ($promo->diskon / 100) * $harga_asli;
            } else {
                $potongan = $promo->diskon;
            }

            $total_harga -= $potongan;
            $promo_id = $promo->id;

            if ($promo->batas_penggunaan) {
                $jumlah_terpakai = Register::where('promo_id', $promo->id)->count();
                if ($jumlah_terpakai >= $promo->batas_penggunaan) {
                    $promo_id = null; // Promo tidak jadi digunakan
                    $total_harga = $harga_asli; // Harga kembali normal
                }
            }
        }

        Register::create([
            'nama_cust' => $request->nama_cust,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'nik' => $request->nik,
            'paket_id' => $request->paket_id,
            'foto_ktp' => $ktpPath,
            'selfie_ktp' => $selfiePath,
            'foto_rumah' => $rumahPath,
            'prov_id' => $request->prov_id,
            'kab_id' => $request->kab_id,
            'kec_id' => $request->kec_id,
            'desa_id' => $request->desa_id,
            'alamat_lengkap' => $request->alamat_lengkap,
            'kebutuhan' => $request->kebutuhan,
            'tanggal_pemasangan' => $request->tanggal_pemasangan,
            'total_harga' => $total_harga,
            'promo_id' => $promo_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        if ($promo_id) {
            Promo::where('id', $promo_id)->increment('jumlah_digunakan');
        }

        return redirect()->back()->with('success', 'Data berhasil dikirim!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kab;
use App\Models\Kec;
use App\Models\Prov;
use App\Imports\ProvImport;
use App\Imports\KabImport;
use App\Imports\KecImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class AlamatController extends Controller
{
    public function prov() {
        $prov = Prov::paginate(10);
        return view('alamat.prov.prov', compact('prov'));
    }    

    public function provStore(Request $request){
        $request->validate([
            'nama_prov' => 'required|string|max:225',
        ]);

        Prov::create([
            'nama_prov' => $request->nama_prov,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function provUpdate(Request $request, $id) {
        $request->validate([
            'nama_prov' => 'required|string|max:225',
        ]);
    
        $prov = Prov::findOrFail($id);
        
        $prov->update([
            'nama_prov' => $request->nama_prov,
        ]);
    
        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }
    

    public function destroy(Prov $prov) {
        $prov->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function kab() {
        $kab = Kab::all();
        $prov = Prov::all();
        return view('alamat.kab.kab', compact('kab', 'prov'));
    }

    public function kabStore(Request $request)
    {
        $request->validate([
            'nama_kab' => 'required|string|max:255',
            'prov_id' => 'required|exists:provs,id'
        ]);
    
        Kab::create([
            'nama_kab' => $request->nama_kab,
            'prov_id' => $request->prov_id
        ]);
    
        return back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function kabUpdate(Request $request, $id) {
        $request->validate([
            'nama_kab' => 'required|string|max:255',
            'prov_id' => 'required|exists:provs,id' 
        ]);
    
        $kab = Kab::findOrFail($id);
        
        $kab->update([
            'nama_kab' => $request->nama_kab,
            'prov_id' => $request->prov_id,
        ]);
    
        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }
    

    public function kabDestroy(Kab $kab) {
        $kab->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
    

    public function kec(){
        $kec = Kec::with('kab.prov')->paginate(10);
        $kab = Kab::all();
        $prov = Prov::all();
        return view('alamat.kec.kec', compact('kec', 'kab', 'prov'));
    }

    public function getKabupaten($prov_id) //untuk mengambil kabupaten atau difilter
    {
        $kabupaten = Kab::where('prov_id', $prov_id)->get();
        return response()->json($kabupaten);
    }

    public function getKecamatan($kab_id) {
        $kecamatan = Kec::where('kab_id', $kab_id)->get();
        return response()->json($kecamatan);
    }

    public function getDesa($kec_id){
        $desa = Desa::where('kec_id', $kec_id)->get();
        return response()->json($desa);
    }

    public function kecStore(Request $request) {
        $request->validate([
            'nama_kec' => 'required|string|max:255',
            'kab_id' => 'required|exists:kabs,id'
        ]);

        Kec::create([
            'nama_kec' => $request->nama_kec,
            'kab_id' => $request->kab_id
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function kecUpdate(Request $request, $id) {
        $request->validate([
            'nama_kec' => 'required|string|max:255',
            'kab_id' => 'required|kabs|id'
        ]);

        $kec = Kec::findOrFail($id);

        $kec->create([
            'nama_kec' => $request->nama_kec,
            'kab_id' => $request->kab_id
        ]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    public function kecDestroy(Kec $kec){
        $kec->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
    
    public function desa(){
        $desa = Desa::all();
        $kec = Kec::all();
        $kab = Kab::all();
        $prov = Prov::all();
        return view('alamat.desa.desa', compact('desa', 'kec', 'kab', 'prov'));
    }

    public function desaStore(Request $request) {
        $request->validate([
            'nama_desa' => 'required|string|max:225',
            'kec_id' => 'required|exists:kecs,id'
        ]);

        Desa::create([
            'nama_desa' => $request->nama_desa,
            'kec_id' => $request->kec_id,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditembahkan!');
    }

    //import
    public function importProvinsi(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new ProvImport, $request->file('file'));

        return back()->with('success', 'Data provinsi berhasil diimpor!');
    }

    public function importKabupaten(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new KabImport, $request->file('file'));

        return back()->with('success', 'Data kabupaten berhasil diimpor!');
    }

    public function importKecamatan(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new KecImport, $request->file('file'));

        return back()->with('success', 'Data kecamatan berhasil diimpor!');
    }
}
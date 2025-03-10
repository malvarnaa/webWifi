<?php

namespace App\Http\Controllers;

use App\Models\Kab;
use App\Models\Kec;
use App\Models\Prov;
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
    
}
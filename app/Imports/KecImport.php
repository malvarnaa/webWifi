<?php

namespace App\Imports;

use App\Models\Prov;
use App\Models\Kab;
use App\Models\Kec;
use Maatwebsite\Excel\Concerns\ToModel;

class KecImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $prov = Prov::where('nama_prov', $row[0])->first();
        $kab = Kab::where('nama_kab', $row[1])
                ->where('prov_id', $prov?->id)
                ->first();
        if (!$prov || !$kab) return null;
        return new Kec([
            'nama_kec' => $row[2],
            'kab_id' => $kab->id,
            'prov_id' => $prov->id,
        ]);
    }
}

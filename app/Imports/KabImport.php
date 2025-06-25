<?php

namespace App\Imports;

use App\Models\Kab;
use App\Models\Prov;
use Maatwebsite\Excel\Concerns\ToModel;

class KabImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $provinsi = Prov::where('nama_prov', $row[0])->first();

        if (!$provinsi) return null;

        return new Kab([
            'nama_kab' => $row[1],
            'prov_id' => $provinsi->id,
        ]);
    }
}

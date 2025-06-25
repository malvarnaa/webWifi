<?php

namespace App\Imports;

use App\Models\Prov;
use Maatwebsite\Excel\Concerns\ToModel;

class ProvImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Prov([
            'nama_prov' => $row[0],
        ]);
    }
}

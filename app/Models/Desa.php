<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $table = 'desas';

    protected $fillable = [
        'kec_id',
        'nama_desa',
    ];

    public function kec()
    {
        return $this->belongsTo(Kec::class, 'kec_id');
    }

    // Ambil kabupaten lewat kecamatan
    public function kab()
    {
        return $this->hasOneThrough(
            Kab::class,       // Model tujuan akhir
            Kec::class,       // Model perantara
            'id',             // id di kecamatan (relasi ke kab)
            'id',             // id di kabupaten
            'kec_id',         // foreign key di desa
            'kab_id'          // foreign key di kec
        );
    }

    public function getProvAttribute()
    {
        return $this->kec->kab->prov ?? null;
    }

        public function registers()
    {
        return $this->hasMany(Register::class, 'desa_id', 'id');
    }
}

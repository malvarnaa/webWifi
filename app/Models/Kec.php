<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kec extends Model
{
    use HasFactory;
    protected $table = 'kecs';
    protected $fillable = [
        'nama_kec',
        'kab_id'
    ];

    public function kab()
    {
        return $this->belongsTo(Kab::class);
    }

    public function prov()
    {
        return $this->hasOneThrough(Prov::class, Kab::class, 'id', 'id', 'kab_id', 'prov_id');
    }

    public function registers()
    {
        return $this->hasMany(Register::class, 'kec_id', 'id');
    }
}

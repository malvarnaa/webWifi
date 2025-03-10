<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kab extends Model
{
    use HasFactory;

    protected $table = 'kabs';
    protected $fillable = [
        'nama_kab',
        'prov_id'
    ];

    public function prov()
    {
        return $this->belongsTo(Prov::class);
    }

    public function kec()
    {
        return $this->hasMany(Kec::class);
    }
    public function registers()
    {
        return $this->hasMany(Register::class, 'kab_id', 'id');
    }
}

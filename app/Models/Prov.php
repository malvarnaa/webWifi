<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prov extends Model
{
    use HasFactory;

    protected $table = 'provs';
    protected $fillable = [
        'nama_prov'
    ];

    public function kab()
    {
        return $this->hasMany(Kab::class);
    }

    public function registers()
    {
        return $this->hasMany(Register::class, 'prov_id', 'id');
    }
}

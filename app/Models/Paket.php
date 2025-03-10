<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'pakets';
    protected $fillable = [
        'nama_paket',
        'kecepatan',
        'deskripsi',
        'harga'
    ];

    public function registers()
    {
        return $this->hasMany(Register::class, 'paket_id');
    }
}

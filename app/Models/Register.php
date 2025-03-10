<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    use HasFactory;

    protected $table = 'registers';
    protected $fillable = [
        'nama_cust',
        'nomor_hp',
        'email',
        'paket_id',
        'prov_id',
        'kab_id',
        'kec_id',
        'alamat_lengkap',
        'kebutuhan',
        'tanggal_pemasangan',
        'total_harga',
        'latitude',
        'longitude',
    ];

    public function prov()
    {
        return $this->belongsTo(Prov::class, 'prov_id', 'id');
    }

    public function kab()
    {
        return $this->belongsTo(Kab::class, 'kab_id', 'id');
    }

    public function kec()
    {
        return $this->belongsTo(Kec::class, 'kec_id', 'id');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id', 'id');
    }
}

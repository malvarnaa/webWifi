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
        'nik',
        'foto_ktp',
        'selfie_ktp',
        'foto_rumah',
        'paket_id',
        'prov_id',
        'kab_id',
        'kec_id',
        'desa_id',
        'alamat_lengkap',
        'kebutuhan',
        'tanggal_pemasangan',
        'total_harga',
        'latitude',
        'longitude',
        'status',
        'status_kepelangganan',
        'tanggal_aktif',
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
    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }

    public function tagihan()
{
    return $this->hasMany(TagihanBulanan::class);
}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanBulanan extends Model
{
    use HasFactory;

    protected $table = 'tagihan_bulanan';

    protected $fillable = [
        'register_id',
        'bulan',
        'jumlah',
        'status',
        'bukti_transfer',
        'jatuh_tempo',
        'tanggal_diterima',
    ];

    public function register()
    {
        return $this->belongsTo(Register::class);
    }
}

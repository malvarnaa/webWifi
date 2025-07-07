<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promo extends Model
{
    use HasFactory;

    protected $table = 'promo';

    protected $fillable = [
        'nama_promo',
        'deskripsi',
        'jenis_promo',
        'diskon',
        'tipe_diskon',
        'minimal_pembelian',
        'waktu_mulai',
        'waktu_berakhir',
        'batas_penggunaan',
        'jumlah_digunakan',
        'gambar',
        'limit_per_user',
        'paket_id',
        'aktif',
    ];

    public function getStatusAttribute()
    {
        $now = Carbon::now('Asia/Jakarta');
    
        if ($now->lt(Carbon::parse($this->waktu_mulai))) {
            return 'Belum Aktif';
        }
    
        if ($now->gt(Carbon::parse($this->waktu_berakhir))) {
            return 'Nonaktif';
        }
    
        return 'Aktif';
    }
    
}

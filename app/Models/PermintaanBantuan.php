<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/PermintaanBantuan.php
class PermintaanBantuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'jenis', 'subjek', 'kategori', 'deskripsi', 'waktu', 'status', 'catatan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


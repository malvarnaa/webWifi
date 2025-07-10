<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $fillable = [
        'user_id',
        'logged_in_at',
        'ip_address',
        'user_agent',
        'session_id', 
        'location',   
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
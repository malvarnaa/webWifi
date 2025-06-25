<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $table = 'module';
    protected $fillable = [
        'module_image',
        'module_name',
        'module_description',
        'index_order',
    ];
}

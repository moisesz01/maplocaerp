<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanalVenta extends Model
{
    use HasFactory;
    protected $table = 'canal_venta';
    protected $fillable = [
        'id', 
        'nombre', 
        'CVTA32'
    ];
}

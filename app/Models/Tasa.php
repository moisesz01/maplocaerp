<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasa extends Model
{
    use HasFactory;
    protected $table = 'tasas_cambio';
    protected $fillable = [
        'id', 
        'MOEX0B', 
        'DESC0B', 
        'TASA0B'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioCompetencia extends Model
{
    use HasFactory;
    protected $table = 'precios_competencia';
    protected $fillable = [
        'id', 
        'fecha',
        'codigo_articulo',
        'nombre_articulo',
        'competidor',
        'precio',
        'tipo_precio'
    ];
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
    protected $table = 'inventario';
    protected $fillable = [
        'id',
        'codigo',
        'articulo',
        'unidad_medida',
        'peso',
        'precio',
        'linea',
        'disponible',
        'codigo_almacen',
        'peso_conversion',
        'ancho_conversion',
        'alto_espesor_conversion',
        'largo',
        'estandar' 
    ];
}


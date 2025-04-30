<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleDocumento extends Model
{
    use HasFactory;
    protected $table = 'detalle_documentos';
    protected $fillable = [
        'id', 
        'codigo_articulo', 
        'nombre_articulo', 
        'unidad_medida',
        'peso',
        'cantidad', 
        'precio', 
        'precio_base', 
        'costo', 
        'documento_id',
        'porcentaje_descuento',
        'tipo'
    ];
    
}

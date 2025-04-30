<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccionVendedor extends Model
{
    use HasFactory;
    protected $table = 'acciones_vendedores';
    protected $fillable = [
        'id', 
        'nombre',
        'tipo_accion_id'
    ];
    public function tipo_accion()
    {
        return $this->belongsTo(TipoAccionVendedor::class,'tipo_accion_id');
    }
    public function planificaciones()
    {
        return $this->hasMany(Planificacion::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planificacion extends Model
{
    use HasFactory;
    protected $table = 'planificaciones';
    protected $fillable = [
        'id',
        'fecha_inicio',
        'fecha_fin',
        'user_id',
        'cliente_id',
        'accion_vendedor_id'
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'cliente_id');
    }
    public function accion()
    {
        return $this->belongsTo(AccionVendedor::class,'accion_vendedor_id');
    }
    public function vendedor()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}

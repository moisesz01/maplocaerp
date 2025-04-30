<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoAccionVendedor extends Model
{
    use HasFactory;
    protected $table = 'tipo_acciones_vendedores';
    protected $fillable = [
        'id', 
        'nombre'
    ];
    public function acciones()
    {
        return $this->hasMany(AccionVendedor::class);
    }
}

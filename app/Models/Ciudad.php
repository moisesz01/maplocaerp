<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    use HasFactory;
    protected $table = 'ciudades';
    protected $fillable = [
        'id',
        'nombre',
        'estado_id',
        'ESTA15', 
        'CIUD16'
    ];
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
    public function estado()
    {
        return $this->belongsTo(Estado::class,'estado_id');
    }
}

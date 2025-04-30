<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    use HasFactory;
    protected $table = 'almacenes';
    protected $fillable = [
        'id',
        'nombre',
        'codigo',
        'direccion',
        'whastapp'
    ];
    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
    public function facturadores()
    {
        return $this->hasMany(AlmacenFacturador::class, 'almacen_id');
    }
}

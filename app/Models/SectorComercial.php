<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectorComercial extends Model
{
    use HasFactory;
    protected $table = 'sectores_comerciales';
    protected $fillable = [
        'id', 
        'nombre',
        'SCCM10'
    ];
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
}

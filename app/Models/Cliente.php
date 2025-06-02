<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $table = 'clientes';
    protected $fillable = [
        'id', 
        'nombre', 
        'tipo_documento', 
        'numero_documento', 
        'ciudad_id', 
        'sector_comercial_id',
        'latitud',
        'longitud',
        'url_foto',
        'correo',
        'telefono',
        'direccion',
        'vendedor_id',
        'observaciones',
        'denominacion_comercial',
        'persona_contacto',
        'cargo_profesion',
        'estado'
    ];
   
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class,'ciudad_id');
    }
    public function planificaciones()
    {
        return $this->hasMany(Planificacion::class);
    }
    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }
    public function visitas()
    {
        return $this->hasMany(Visita::class);
    }
    public function sector(){
        return $this->belongsTo(SectorComercial::class,'sector_comercial_id');
    }
    public function vendedor(){
        return $this->belongsTo(User::class,'vendedor_id');
    }
}

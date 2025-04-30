<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    use HasFactory;
    protected $table = 'visitas';
    protected $fillable = [
        'id',
        'fecha_checkin',
        'fecha_checkout',
        'latitud',
        'longitud',
        'cliente_id',
        'notas'
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'cliente_id');
    }
}

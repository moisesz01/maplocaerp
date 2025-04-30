<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlmacenFacturador extends Model
{
    use HasFactory;
    protected $table = 'almacenes_facturadores';
    protected $fillable = [
        'id',
        'almacen_id',
        'user_id'
    ];
    public function almacen()
    {
        return $this->belongsTo(Almacen::class,'almacen_id');
    }
    public function vendedor()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}

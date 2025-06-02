<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;
    public function vendedor()
    {
        return $this->belongsTo(User::class,'vendedor_id');
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'cliente_id');
    }
    public function tipo()
    {
        return $this->belongsTo(TipoDocumento::class,'tipo_documento_id');
    }
}

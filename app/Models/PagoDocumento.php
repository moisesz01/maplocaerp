<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoDocumento extends Model
{
    use HasFactory;
    protected $table = 'pagos_documentos';
    protected $fillable = [
        'id',
        'documento_id',
        'metodo_pago_id',
        'monto'
    ];
    public function metodo()
    {
        return $this->belongsTo(MetodoPago::class,'metodo_pago_id');
    }
    public function documento()
    {
        return $this->belongsTo(Documento::class,'documento_id');
    }
}

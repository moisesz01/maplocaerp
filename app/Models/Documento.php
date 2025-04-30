<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;
    protected $table = 'documentos';
    protected $fillable = [
        'id',
        'fecha_creacion', 
        'vendedor_id', 
        'cliente_id', 
        'tipo_documento_id', 
        'metodo_pago_id', 
        'visita_id',
        'forma_pago_id',
        'notas',
        'porcentaje_descuento',
        'status_id',
        'descuento_individual',
        'con_iva',
        'comentario_estado',
        'dias_credito',
        'contribuyente_especial',
        'moneda_extranjera',
        'tasa',
    ];
    public function status()
    {
        return $this->belongsTo(Status::class,'metodo_pago_id');
    }
    public function metodo()
    {
        return $this->belongsTo(MetodoPago::class,'metodo_pago_id');
    }
    public function forma()
    {
        return $this->belongsTo(FormaPago::class,'forma_pago_id');
    }
    public function tipo()
    {
        return $this->belongsTo(TipoDocumento::class,'tipo_documento_id');
    }
    public function vendedor()
    {
        return $this->belongsTo(User::class,'vendedor_id');
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'cliente_id');
    }
}

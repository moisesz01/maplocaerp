<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadosDocumentos extends Model
{
    use HasFactory;
    protected $table = 'estados_documentos';
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}

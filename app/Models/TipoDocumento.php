<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use HasFactory;
    protected $table = 'tipos_documentos';
    protected $fillable = [
        'id',
        'nombre',
    ];
    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}

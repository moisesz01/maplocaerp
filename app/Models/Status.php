<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $table = 'status';
    protected $fillable = [
        'id',
        'nombre', 
        'tipo_status_id'
    ];
    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
    public function tipo()
    {
        return $this->belongsTo(TipoStatus::class,'tipo_status_id');
    }
}

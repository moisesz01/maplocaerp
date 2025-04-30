<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoStatus extends Model
{
    use HasFactory;
    protected $table = 'tipo_status';
    protected $fillable = [
        'id',
        'nombre', 
    ];
    public function status()
    {
        return $this->hasMany(Documento::class);
    }
}

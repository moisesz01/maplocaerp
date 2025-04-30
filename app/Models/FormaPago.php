<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    use HasFactory;
    protected $table = 'formas_pagos';
    protected $fillable = [
        'id',
        'nombre',
    ];
    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}

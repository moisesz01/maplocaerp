<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    public function categorizacionAs400()
    {
        return $this->hasMany(CategorizacionAs400::class);
    }

    use HasFactory;
}

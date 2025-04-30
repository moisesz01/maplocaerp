<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoria extends Model
{
    protected $table = 'sub_categorias';
    protected $fillable = [
        'subcategoria',
        'categoria_id',
        'categoria_as400',
        'imagen'
    ];

    public function categorizacionAs400()
    {
        return $this->hasMany(CategorizacionAs400::class, 'subcategoria_id');
    }
    public function categoria()
{
    return $this->belongsTo(Categoria::class, 'categoria_id');
}
    use HasFactory;
}

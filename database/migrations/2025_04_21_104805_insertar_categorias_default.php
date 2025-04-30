<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $categorias = [
            'Galvanizado',
            'Bobina Laminada en Frío',
            'Bobina Laminada en Caliente',
            'Tubería',
            'Materiales complementarios',
            'Ángulos',
            'Pletinas',
            'Alambrón',
            'Barras',
            'Cabillas',
            'Cerchas',
            'Malla truckson',
            'Vigas o perfiles',
        ];

        foreach ($categorias as $categoria) {
            DB::table('categorias')->insert([
                'categoria' => $categoria,
                'descripcion' => '', 
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $categorias = [
            'Galvanizado',
            'Bobina Laminada en Frío',
            'Bobina Laminada en Caliente',
            'Tubería',
            'Materiales complementarios',
            'Ángulos',
            'Pletinas',
            'Alambrón',
            'Barras',
            'Cabillas',
            'Cerchas',
            'Malla truckson',
            'Vigas o perfiles',
        ];

        DB::table('categorias')->whereIn('categoria', $categorias)->delete();
    }
};

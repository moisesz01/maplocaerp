<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $estados = [
            ['nombre' => 'Amazonas'],
            ['nombre' => 'Anzoátegui'],
            ['nombre' => 'Apure'],
            ['nombre' => 'Aragua'],
            [ 'nombre' => 'Barinas'],
            ['nombre' => 'Bolívar'],
            [ 'nombre' => 'Carabobo'],
            ['nombre' => 'Cojedes'],
            ['nombre' => 'Delta Amacuro'],
            ['nombre' => 'Distrito Capital'],
            [ 'nombre' => 'Falcón'],
            [ 'nombre' => 'Guárico'],
            [ 'nombre' => 'Lara'],
            [ 'nombre' => 'Mérida'],
            [ 'nombre' => 'Miranda'],
            [ 'nombre' => 'Monagas'],
            [ 'nombre' => 'Nueva Esparta'],
            [ 'nombre' => 'Portuguesa'],
            [ 'nombre' => 'Sucre'],
            [ 'nombre' => 'Táchira'],
            [ 'nombre' => 'Trujillo'],
            [ 'nombre' => 'Vargas'],
            [ 'nombre' => 'Yaracuy'],
            [ 'nombre' => 'Zulia'],
        ];

        foreach ($estados as $estado) {
            Estado::create($estado);
        }
    }
}

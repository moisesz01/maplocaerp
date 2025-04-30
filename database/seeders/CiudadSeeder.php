<?php

namespace Database\Seeders;

use App\Models\Ciudad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CiudadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ciudades = [
            ['nombre' => 'Puerto Ayacucho', 'estado_id' => 1],
            ['nombre' => 'Barcelona', 'estado_id' => 2],
            ['nombre' => 'San Fernando de Apure', 'estado_id' => 3],
            ['nombre' => 'Maracay', 'estado_id' => 4],
            ['nombre' => 'Barinas', 'estado_id' => 5],
            ['nombre' => 'Ciudad Bolívar', 'estado_id' => 6],
            ['nombre' => 'Valencia', 'estado_id' => 7],
            ['nombre' => 'San Carlos', 'estado_id' => 8],
            ['nombre' => 'Tucupita', 'estado_id' => 9],
            ['nombre' => 'Caracas', 'estado_id' => 10],
            ['nombre' => 'Coro', 'estado_id' => 11],
            ['nombre' => 'San Juan de Los Morros', 'estado_id' => 12],
            ['nombre' => 'Barquisimeto', 'estado_id' => 13],
            ['nombre' => 'Mérida', 'estado_id' => 14],
            ['nombre' => 'Los Teques', 'estado_id' => 15],
            ['nombre' => 'Maturín', 'estado_id' => 16],
            ['nombre' => 'La Asunción', 'estado_id' => 17],
            ['nombre' => 'Guanare', 'estado_id' => 18],
            ['nombre' => 'Cumaná', 'estado_id' => 19],
            ['nombre' => 'San Cristóbal', 'estado_id' => 20],
            ['nombre' => 'Trujillo', 'estado_id' => 21],
            ['nombre' => 'La Guaira', 'estado_id' => 22],
            ['nombre' => 'San Felipe', 'estado_id' => 23],
            ['nombre' => 'Maracaibo', 'estado_id' => 24],
        ];

        foreach ($ciudades as $ciudad) {
            Ciudad::create($ciudad);
        }
    }
}

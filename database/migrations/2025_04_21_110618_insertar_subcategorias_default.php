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
        $subcategoriasPorCategoria = [
            'Galvanizado' => [
                'Lámina lisa galvanizada.',
                'Techos de Zinc',
                'Losa Sigaldeck®',
            ],
            'Bobina Laminada en Frío' => [
                'Lámina pulida',
                'Bobina',
            ],
            'Bobina Laminada en Caliente' => [
                'Lámina de hierro negro.',
                'Chapas.',
                'Planchones',
                'Lámina estriada',
            ],
            'Tubería' => [
                'Tubos pulidos.',
                'Tubos hierro negro.',
                'Tubos estructurales',
                'Tubos de ventilación',
            ],
            'Materiales complementarios' => [
                'Fondo anticorrosivo.',
                'Pinturas.',
                'Discos de corte y desbaste.',
                'Bisagras.',
                'Ganchos de techo.',
                'Electrodos.',
            ],
            'Ángulos' => [
                'Laminados',
                'Siderúrgicos.',
            ],
            'Pletinas' => [
                'Lisas',
                'Perforadas',
            ],
            'Cabillas' => [
                'Sizuca',
                'Sidor',
                'Sidetur',
            ],
            'Vigas o perfiles' => [
                'Importados',
                'Nacionales',
                'Formados en frío.',
            ],
        ];

        foreach ($subcategoriasPorCategoria as $nombreCategoria => $subcategorias) {
            $categoria = DB::table('categorias')->where('categoria', $nombreCategoria)->first();

            if ($categoria) {
                foreach ($subcategorias as $sub) {
                    DB::table('sub_categorias')->insert([
                        'subcategoria' => $sub,
                        'categoria_id' => $categoria->id,
                    ]);
                }
            } else {
                echo "⚠️  Categoría no encontrada: $nombreCategoria\n";
            }
        }
    }

   
    public function down(): void
    {
        Schema::table('sub_categorias', function (Blueprint $table) {
            $table->dropColumn('categoria_as400');
        });
    }
};

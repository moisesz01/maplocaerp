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
        Schema::table('documentos', function (Blueprint $table) {
            $table->integer('descuento_individual')->default(0)->comment('permite registrar si existe descuento individual de uno o mas productos, valor cero no hay productos con este criterio y el valor 1 si hay productos con este criterio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn('descuento_individual');
        });
    }
};

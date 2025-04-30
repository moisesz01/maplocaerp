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
        Schema::create('precios_competencia', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('codigo_articulo',15)->nullable();
            $table->string('nombre_articulo',50)->nullable();
            $table->string('competidor');
            $table->float('precio');
            $table->string('tipo_precio',10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precios_competencia');
    }
};

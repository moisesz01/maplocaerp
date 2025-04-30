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
        Schema::create('detalle_documentos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_articulo',15);
            $table->string('nombre_articulo',50);
            $table->integer('cantidad');
            $table->float('precio');
            $table->float('costo');
            $table->unsignedBigInteger('documento_id')->nullable();
            $table->foreign('documento_id')
                ->references('id')->on('documentos')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_documentos');
    }
};

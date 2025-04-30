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
        Schema::create('acciones_vendedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',80);
            $table->unsignedBigInteger('tipo_accion_id')->nullable();
            $table->foreign('tipo_accion_id')
                ->references('id')->on('tipo_acciones_vendedores')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acciones_vendedores');
    }
};

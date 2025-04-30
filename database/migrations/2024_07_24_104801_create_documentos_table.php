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
        Schema::create('tipos_documentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });
        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_creacion');
            $table->unsignedBigInteger('vendedor_id')->nullable();
            $table->foreign('vendedor_id')
                ->references('id')->on('users')
                ->onDelete('no action');

            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')
                ->references('id')->on('clientes')
                ->onDelete('no action');

            $table->unsignedBigInteger('tipo_documento_id')->nullable();
            $table->foreign('tipo_documento_id')
                ->references('id')->on('tipos_documentos')
                ->onDelete('no action');

            $table->unsignedBigInteger('metodo_pago_id')->nullable();
            $table->foreign('metodo_pago_id')
                ->references('id')->on('metodos_pago')
                ->onDelete('no action');

            $table->unsignedBigInteger('visita_id')->nullable();
            $table->foreign('visita_id')
                ->references('id')->on('visitas')
                ->onDelete('no action');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_documentos');
        Schema::dropIfExists('metodos_pago');
        Schema::dropIfExists('visitas');
        Schema::dropIfExists('documentos');
    }
};

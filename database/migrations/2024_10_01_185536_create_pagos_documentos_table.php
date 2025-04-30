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
        Schema::create('pagos_documentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('documento_id')->nullable();
            $table->foreign('documento_id')
                ->references('id')->on('documentos')
                ->onDelete('cascade');
            $table->unsignedBigInteger('metodo_pago_id')->nullable();
            $table->foreign('metodo_pago_id')
                ->references('id')->on('metodos_pago')
                ->onDelete('no action');
            $table->float('monto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_documentos');
    }
};

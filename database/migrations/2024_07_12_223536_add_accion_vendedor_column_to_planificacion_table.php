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
        Schema::table('planificaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('accion_vendedor_id')->nullable();
            $table->foreign('accion_vendedor_id')
                ->references('id')->on('acciones_vendedores')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planificaciones', function (Blueprint $table) {
            $table->dropForeign(['accion_vendedor_id']);
            $table->dropColumn('accion_vendedor_id');
        });
    }
};

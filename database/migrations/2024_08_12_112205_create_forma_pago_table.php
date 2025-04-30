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
        Schema::create('formas_pagos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });
      

        Schema::table('documentos', function (Blueprint $table) {
            $table->unsignedBigInteger('forma_pago_id')->nullable();
            $table->foreign('forma_pago_id')
                ->references('id')->on('formas_pagos')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forma_pago');
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropForeign(['forma_pago_id']);
            $table->dropColumn('forma_pago_id');
        });
    }
};

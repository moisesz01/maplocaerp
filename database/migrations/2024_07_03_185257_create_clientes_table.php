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
        Schema::create('sectores_comerciales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->timestamps();
        });
        Schema::create('tipo_status', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->timestamps();
        });
        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->integer('orden')->nullable();
            $table->unsignedBigInteger('tipo_status_id')->nullable();
            $table->foreign('tipo_status_id')
                ->references('id')->on('tipo_status')
                ->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->timestamps();
        });
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->unsignedBigInteger('estado_id')->nullable();
            $table->foreign('estado_id')
                ->references('id')->on('estados')
                ->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->string('tipo_documento',1)->nullable();
            $table->integer('numero_documento')->nullable();
            $table->unsignedBigInteger('ciudad_id')->nullable();
            $table->foreign('ciudad_id')
                ->references('id')->on('ciudades')
                ->onDelete('cascade');
            $table->unsignedBigInteger('sector_comercial_id')->nullable();
            $table->foreign('sector_comercial_id')
                ->references('id')->on('sectores_comerciales')
                ->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sectores_comerciales');
        Schema::dropIfExists('tipo_status');
        Schema::dropIfExists('status');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('ciudades');
        Schema::dropIfExists('estados');
    }
};

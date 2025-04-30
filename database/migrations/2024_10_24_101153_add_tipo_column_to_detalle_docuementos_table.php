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
        Schema::table('detalle_documentos', function (Blueprint $table) {
            $table->string('tipo',1)->default('P')->comment('campo para determinar si es producto o servicio P: producto; S: servicio');    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_documentos', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};

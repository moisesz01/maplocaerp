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
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('denominacion_comercial',100)->nullable();
            $table->string('persona_contacto',100)->nullable();
            $table->string('cargo_profesion',100)->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('denominacion_comercial');
            $table->dropColumn('persona_contacto');
            $table->dropColumn('cargo_profesion');
        });
    }
};

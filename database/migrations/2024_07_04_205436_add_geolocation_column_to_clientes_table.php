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
            $table->float('latitud')->nullable();
            $table->float('longitud')->nullable();
            $table->string('correo',100)->nullable();
            $table->string('telefono',15)->nullable();
            $table->text('url_foto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['latitud']);
            $table->dropColumn(['longitud']);
            $table->dropColumn(['url_foto']);
            $table->dropColumn(['correo']);
            $table->dropColumn(['telefono']);
        });
    }
};

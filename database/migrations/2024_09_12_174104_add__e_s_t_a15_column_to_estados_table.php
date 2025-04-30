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
        Schema::table('estados', function (Blueprint $table) {
            $table->string('ESTA15',2)->nullable()->comment('Código identificador asociado al estado en el as400');
        });
        Schema::table('ciudades', function (Blueprint $table) {
            $table->string('ESTA15',2)->nullable()->comment('Código identificador asociado al estado en el as400');
            $table->string('CIUD16',2)->nullable()->comment('Código identificador asociado al estado en el as400');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estados', function (Blueprint $table) {
            $table->dropColumn('ESTA15');
        });
        Schema::table('ciudades', function (Blueprint $table) {
            $table->dropColumn('ESTA15');
            $table->dropColumn('CIUD16');
        });
    }
};

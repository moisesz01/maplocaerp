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
        Schema::table('sectores_comerciales', function (Blueprint $table) {
            $table->string('SCCM10',2)->nullable()->comment('Código de sector comercial en el as400');            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sectores_comerciales', function (Blueprint $table) {
            $table->dropColumn('SCCM10');
        });
    }
};

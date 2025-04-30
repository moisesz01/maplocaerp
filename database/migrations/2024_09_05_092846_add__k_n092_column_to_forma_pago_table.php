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
        Schema::table('formas_pagos', function (Blueprint $table) {
            $table->integer('CDVT92')->nullable()->comment('Número identificador asociado a las formas de pago en el el as400');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formas_pagos', function (Blueprint $table) {
            $table->dropColumn('CDVT92');
        });
    }
};

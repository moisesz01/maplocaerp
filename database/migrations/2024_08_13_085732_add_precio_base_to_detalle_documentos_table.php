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
        
        Schema::table('documentos', function (Blueprint $table) {
            $table->float('porcentaje_descuento')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id')
                ->references('id')->on('status')
                ->onDelete('no action');
        });
        Schema::table('detalle_documentos', function (Blueprint $table) {
            $table->float('precio_base')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_documentos', function (Blueprint $table) {
            $table->dropColumn('precio_base');
        });
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
    }
};

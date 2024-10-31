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
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('codigo')->unique()->after('estado_pago'); // Agrega el campo 'codigo'
            $table->enum('tipo_pago', ['consumo', 'multa'])->after('codigo'); // Agrega el campo 'tipo_pago'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn('tipo_pago'); // Elimina el campo 'tipo_pago' si se revierte la migración
            $table->dropColumn('codigo'); // Elimina el campo 'codigo' si se revierte la migración
        });
    }
};

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
        Schema::create('pago_consumo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pago_id')->constrained('pagos')->onDelete('cascade'); // Relaciona con la tabla 'pagos'
            $table->foreignId('consumo_id')->constrained('consumos')->onDelete('cascade'); // Relaciona con la tabla 'consumos'
            $table->decimal('monto_pagado', 10, 2); // Monto total del consumo pagado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_consumo');
    }
};

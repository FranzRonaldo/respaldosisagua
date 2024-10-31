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
        Schema::create('recibos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pago_id'); // Relacionado con el pago
            $table->string('codigo_recibo')->unique(); // Código único del recibo
            $table->date('fecha_emision'); // Fecha de emisión del recibo
            $table->string('pdf_path'); // Ruta al archivo PDF del recibo
            $table->timestamps();
    
            $table->foreign('pago_id')->references('id')->on('pagos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recibos');
    }
};

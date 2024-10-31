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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas'); // Relaciona con la tabla 'personas'
            $table->foreignId('propiedad_id')->constrained('propiedades'); // Relaciona con la tabla 'propiedades'
            $table->date('fecha_pago'); // Fecha en que se realizó el pago
            $table->boolean('estado_pago')->default(true); // Estado del pago (true = pagado, false = pendiente)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

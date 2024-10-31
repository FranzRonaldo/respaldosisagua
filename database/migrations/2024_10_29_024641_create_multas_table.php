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
        Schema::create('multas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propiedad_id')->constrained('propiedades')->onDelete('cascade'); // Clave foránea
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade'); // Clave foránea
            $table->foreignId('asistencia_id')->constrained('asistencias')->onDelete('cascade'); // Clave foránea de asistencia
            $table->decimal('monto', 8, 2); // Monto de la multa
            $table->string('codigo')->unique(); // Código de referencia de la multa
            $table->boolean('pagada')->default(0); // Indica si la multa ha sido pagada
            $table->boolean('bloqueado')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multas');
    }
};

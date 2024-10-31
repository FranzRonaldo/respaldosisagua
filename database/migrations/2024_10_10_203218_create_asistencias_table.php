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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id(); // Primary key
            // Clave foránea que apunta a la tabla propiedades
            $table->foreignId('propiedad_id')->constrained('propiedades')->onDelete('cascade');
            // Clave foránea que apunta a la tabla actividades
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade');
            // Indica si asistió (0 = no, 1 = sí)
            $table->boolean('asistio')->default(0); 
            // Indica si se aplicó multa (0 = no, 1 = sí)
            $table->boolean('multa_aplicada')->default(0); 
            // Indica si la asistencia está bloqueada para modificaciones (0 = no, 1 = sí)
            $table->boolean('bloqueado')->default(0); 

            $table->timestamps(); // Timestamps para created_at y updated_at

            // Asegura que no haya duplicados en la combinación de socio y actividad
            $table->unique(['propiedad_id', 'actividad_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};

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
        Schema::create('propiedades', function (Blueprint $table) {
            $table->id(); // Primary key
            // Identificador de la propiedad (debe ser único)
            $table->string('identificador_propiedad')->unique(); // Nuevo campo identificador de propiedad
            // Código de la propiedad (opcional)
            $table->string('codigo', 20)->nullable()->unique(); // Hacer el campo código opcional
            // Red a la que pertenece la propiedad
            $table->string('red', 40);
            // Ubicación de la propiedad
            $table->string('ubicacion', 40);
            // Fecha de ingreso de la propiedad
            $table->date('fecha_ingreso');
            // Estado de la propiedad (activo o inactivo)
            $table->tinyInteger('estado')->default(1); // 1 = activo, 0 = inactivo
            // Clave foránea que apunta a la tabla personas
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
            $table->timestamps(); // Timestamps para created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propiedades');
    }
};

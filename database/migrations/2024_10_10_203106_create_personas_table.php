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
        Schema::create('personas', function (Blueprint $table) {
            $table->id(); // Primary key
            // Nombre y apellidos
            $table->string('nombre', 80);
            $table->string('papellido', 20)->nullable(); // Primer apellido opcional
            $table->string('sapellido', 20)->nullable(); // Segundo apellido opcional
            // Número de carnet con complemento
            $table->string('numero_carnet', 20)->unique(); // Número de carnet con su complemento (ej. 5784574-AB)
            // Teléfono
            $table->string('telefono', 20);
            // Email único (opcional)
            $table->string('email')->nullable()->unique(); // Email puede ser nulo, pero debe ser único si se proporciona
            // Estado de la persona (activo o inactivo)
            $table->tinyInteger('estado')->default(1); // 1 = activo, 0 = inactivo
            $table->timestamps(); // Timestamps para created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};

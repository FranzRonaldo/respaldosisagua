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
        Schema::create('consumos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propiedad_id')->constrained('propiedades')->onDelete('cascade'); // Clave foránea hacia propiedades
            $table->integer('mes'); // Mes del consumo
            $table->integer('anio'); // Año del consumo
            $table->decimal('lectura_anterior', 8, 2); // Lectura anterior
            $table->decimal('lectura_actual', 8, 2); // Lectura actual
            $table->decimal('consumo', 10, 2)->default(0); // Cantidad de agua consumida en metros cúbicos
            $table->decimal('monto_cobrar', 10, 2)->default(0); // Monto total a cobrar por el consumo
            $table->decimal('monto_total', 10, 2)->default(0); // Suma del monto a pagar por el consumo + multa
            $table->boolean('estado_pago')->default(0); // 0=pendiente, 1=pagado
            $table->boolean('bloqueado')->default(0); // Indica si el consumo está bloqueado para modificaciones
            $table->timestamps();

            // Asegura que no haya duplicados en la combinación de propiedad, mes y año
            $table->unique(['propiedad_id', 'mes', 'anio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumos');
    }
};

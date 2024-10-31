<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'persona_id',
        'propiedad_id',
        'fecha_pago',
        'estado_pago',
        'codigo', // Agregar el campo 'codigo'
        'tipo_pago', // Agregar el campo 'tipo_pago'
    ];

    protected $casts = [
        'fecha_pago' => 'datetime', 
    ];

    // Relaciones

    /**
     * Relación con el modelo Persona
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    /**
     * Relación con el modelo Propiedad
     */
    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class);
    }

    /**
     * Relación con el modelo Consumo a través de la tabla intermedia PagoConsumo
     */
    public function consumos()
    {
        return $this->belongsToMany(Consumo::class, 'pago_consumo')
                    ->withPivot('monto_pagado')
                    ->withTimestamps();
    }

        public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    public static function generarCodigo($tipoPago)
    {
        $ultimoPago = self::where('tipo_pago', $tipoPago)->latest()->first();
        $nuevoNumero = $ultimoPago ? intval(substr($ultimoPago->codigo, -6)) + 1 : 1;
        $prefijo = $tipoPago === 'consumo' ? 'C-' : 'M-';
        return $prefijo . str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);
    }

    ////////

    public function multas()
{
    return $this->belongsToMany(Multa::class, 'pago_multa', 'pago_id', 'multa_id')
                ->withPivot('monto_pagado')
                ->withTimestamps();
}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoConsumo extends Model
{
    use HasFactory;

    protected $table = 'pago_consumo';

    protected $fillable = [
        'pago_id',
        'consumo_id',
        'monto_pagado',
    ];

    // Relaciones

    /**
     * Relación con el modelo Pago
     */
    public function pago()
    {
        return $this->belongsTo(Pago::class);
    }

    /**
     * Relación con el modelo Consumo
     */
    public function consumo()
    {
        return $this->belongsTo(Consumo::class);
    }
}

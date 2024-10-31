<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumo extends Model
{
    use HasFactory;

    protected $table = 'consumos';

    protected $fillable = [
        'propiedad_id',  // Clave foránea hacia propiedades
        'mes',
        'anio',
        'lectura_anterior',
        'lectura_actual',
        'consumo',
        'monto_cobrar',
        'monto_total',
        'estado_pago',
        'bloqueado', // Nuevo campo para bloqueo
    ];

    protected $casts = [
        'estado_pago' => 'boolean',
        'consumo' => 'decimal:2',
        'monto_cobrar' => 'decimal:2',
        'monto_total' => 'decimal:2',
        'lectura_anterior' => 'decimal:2',
        'lectura_actual' => 'decimal:2',
        'bloqueado' => 'boolean', // Aseguramos que el campo bloqueado sea booleano
    ];

    // Relaciones
    public function propiedad()
    {
// return $this->belongsTo(Propiedad::class); // Relación con Propiedad
return $this->belongsTo(Propiedad::class, 'propiedad_id'); // Asegúrate de que el nombre de la clave sea correcto
    }

    public function pagos()
    {
        return $this->belongsToMany(Pago::class, 'pago_consumo')
                    ->withPivot('monto_pagado')
                    ->withTimestamps();
    }
     // Relación con Persona
     public function persona()
     {
         return $this->belongsTo(Persona::class);
     }

    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'propiedad_id', // Campo actualizado para referirse a propiedades
        'actividad_id',
        'asistio',
        'multa_aplicada',
        'bloqueado', // Agregar el campo bloqueado
    ];
    
    protected $casts = [
        'asistio' => 'boolean',
        'multa_aplicada' => 'boolean',
        'bloqueado' => 'boolean', // Asegurarse de que bloqueado se maneje como booleano
    ];

    // Relaciones
    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class);
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }

    public function multa()
    {
        return $this->hasOne(Multa::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model
{
    use HasFactory;

    protected $table = 'propiedades'; // Nombre de la tabla

    protected $fillable = [
        'identificador_propiedad', // Identificador único de la propiedad
        'codigo',                  // Código de la propiedad (opcional)
        'red',                     // Red a la que pertenece la propiedad
        'ubicacion',               // Ubicación de la propiedad
        'fecha_ingreso',          // Fecha de ingreso de la propiedad
        'estado',                  // Estado de la propiedad (activo o inactivo)
        'persona_id',              // ID de la persona asociada
    ];

    // Indicar que 'fecha_ingreso' es una fecha
    protected $casts = [
        'fecha_ingreso' => 'date',
    ];

    // Definir la relación con el modelo Persona
    public function persona()
    {
     //   return $this->belongsTo(Persona::class,'persona_id');
     return $this->belongsTo(Persona::class, 'persona_id'); // Asegúrate de que el nombre de la clave sea correcto
    }

    // Definir la relación con el modelo Consumo
    public function consumos()
    {
        return $this->hasMany(Consumo::class);
    }

    // Relación con el modelo Asistencia
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    // Generar automáticamente 'identificador_propiedad' antes de guardar el modelo
    protected static function booted()
    {
        static::creating(function ($propiedad) {
            if (is_null($propiedad->identificador_propiedad)) {
                $lastId = self::max('id') ?? 0; // Obtener el último ID
                $propiedad->identificador_propiedad = 'A-' . ($lastId + 1); // Generar el identificador
            }
        });
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'propiedad_id');
    }
}

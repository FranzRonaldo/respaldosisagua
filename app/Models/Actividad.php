<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    // Especifica el nombre correcto de la tabla
    protected $table = 'actividades';

    protected $fillable = [
        'nombre_actividad',
        'descripcion',
        'fecha',
        'multa',
    ];

    protected $casts = [
        'fecha' => 'date',
        'multa' => 'decimal:2',
    ];

    //Relación con la tabla 'asistencias'.
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }
}

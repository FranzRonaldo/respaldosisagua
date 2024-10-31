<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multa extends Model
{
    use HasFactory;

    protected $fillable = [
        'propiedad_id',
        'actividad_id',
        'asistencia_id',
        'monto',
        'codigo',
        'pagada',
        'bloqueado',
    ];

    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class);
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }

    public function asistencia()
    {
        return $this->belongsTo(Asistencia::class);
    }
}

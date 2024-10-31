<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';

    protected $fillable = [
        'nombre',
        'papellido',
        'sapellido',
        'numero_carnet',
        'telefono',
        'email',
        'estado',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function propiedades()
    {
        return $this->hasMany(Propiedad::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    use HasFactory;

    protected $fillable = [
        'pago_id',
        'codigo_recibo',
        'fecha_emision',
        'pdf_path',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
    ];

    public function pago()
    {
        return $this->belongsTo(Pago::class);
    }

    // Generar código de recibo único
    protected static function booted()
    {
        static::creating(function ($recibo) {
            $recibo->codigo_recibo = 'R-' . strtoupper(uniqid());
        });
    }
}

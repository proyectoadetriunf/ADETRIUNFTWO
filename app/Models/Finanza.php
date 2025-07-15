<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Finanza extends Model
{
    protected $collection = 'finanzas';

    protected $fillable = [
        'categoria',
        'descripcion',
        'monto',
        'fecha',
        'tipo_archivo',
        'nombre_archivo',
        'comprobante',
        'registrado_por',
        'proyecto_id', // si lo manejas
        'comunidad' // si está disponible
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'monto' => 'float',
    ];
}

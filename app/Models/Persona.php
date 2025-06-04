<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Persona extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'personas';

    protected $fillable = [
        'nomb_per', 'dni', 'fecha_nacimiento', 'sexo',
        'telefono', 'correo', 'direccion',
        'departamento_id', 'municipio_id', 'colonia_id',
    ];
}


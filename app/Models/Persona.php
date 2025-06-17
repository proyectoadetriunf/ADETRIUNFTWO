<?php
namespace App\Models;

use Jenssegers\Mongodb\Auth\User as Authenticatable;

use Laravel\Sanctum\HasApiTokens;

class Persona extends Authenticatable
{
    use HasApiTokens;

    protected $connection = 'mongodb';
    protected $collection = 'personas';

    protected $fillable = [
        'nomb_per', 'dni', 'fecha_nacimiento', 'sexo',
        'telefono', 'correo', 'direccion',
        'departamento_id', 'municipio_id', 'colonia_id',
        'email', 'password', 'rol_id',
    ];
}


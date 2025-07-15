<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Colonia extends Model
{
    protected $collection = 'colonias'; // nombre real de tu colección
    protected $connection = 'mongodb';  // usa esta conexión si tienes varias

    public $timestamps = false;

    protected $fillable = [
        'colonia_id',
        'nombre_col',
        'municipio_id'
    ];
}


<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Rol extends Model
{
    protected $collection = 'persona_roles'; // Colección en MongoDB
    protected $primaryKey = '_id';           // Clave primaria Mongo

    public $timestamps = false;              // No usa created_at/updated_at

    protected $fillable = [
        'rol_id',
        'nombre',
    ];

    
}



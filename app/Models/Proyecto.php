<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class Proyecto extends Model
{
    // Nombre de la tabla (si no usas el nombre por defecto "proyectos")
    protected $table = 'proyectos';

    // Clave primaria personalizada
    protected $primaryKey = 'proyecto_id';

    // Desactiva los timestamps automáticos (created_at, updated_at)
    public $timestamps = false;

    // Campos que se pueden llenar de forma masiva
    protected $fillable = [
        'nombre_proyecto',
        'descripcion_proyecto',
        'anio_proyecto',
        'fecha_creacion_proyecto',
        'fecha_finalizacion',
        'estado_proyecto'
    ];
}


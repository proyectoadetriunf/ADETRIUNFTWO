<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class TipoSolicitud extends Model
{
    protected $table = 'tipos_solicitudes';
    protected $primaryKey = 'tipo_solicitud_id';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_solicitud',
        'requiere_aprobacion',
        'visible_para_beneficiario'
    ];
}

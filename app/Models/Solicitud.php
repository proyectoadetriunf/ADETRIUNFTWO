<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class Solicitud extends Model
{
    protected $table = 'solicitudes'; // Nombre exacto de tu tabla
    protected $primaryKey = 'solicitud_id'; // Clave primaria personalizada

    public $timestamps = false; // No tienes created_at ni updated_at en la tabla

    protected $fillable = [
        'tipo_solicitud',
        'descripcion_solicitud',
        'estado_solicitud',
        'fecha_envio',
        'fecha_respuesta',
        'proyecto_id',
        'beneficiario_id',
        'remitente_id',
        'destinatario_id',
        'respuesta_solicitud',
        'tipo_solicitud_id'
    ];

    // Relaciones opcionales (si las vas a usar luego)
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function beneficiario()
    {
        return $this->belongsTo(Persona::class, 'beneficiario_id');
    }

    public function remitente()
    {
        return $this->belongsTo(Persona::class, 'remitente_id');
    }

    public function destinatario()
    {
        return $this->belongsTo(Persona::class, 'destinatario_id');
    }

    public function tipoSolicitud()
    {
        return $this->belongsTo(TipoSolicitud::class, 'tipo_solicitud_id');
    }
}

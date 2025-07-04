<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class NotificacionPersonalizada extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'notificaciones_personalizadas';
    protected $fillable = [
        'user_id',
        'mensaje',
        'leida',
        'created_at',
    ];
    public $timestamps = false;
}

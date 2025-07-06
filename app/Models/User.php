<?php

namespace App\Models;

use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $connection = 'mongodb'; // Conexión a MongoDB
    protected $collection = 'users';   // Nombre de la colección

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol_id',
        'photo',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Métodos requeridos por JWT
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Mongo usa _id como clave primaria
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function notifications()
    {
        return $this->morphMany(\App\Models\DatabaseNotification::class, 'notifiable')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Forzar notificaciones a usar MongoDB.
     */
    public function routeNotificationForDatabase($notification)
    {
        return (new \App\Models\DatabaseNotification)->setConnection('mongodb');
    }
}


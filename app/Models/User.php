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

    /* ==== Conexión / colección ==== */
    protected $connection = 'mongodb';
    protected $collection = 'users';

    /* ==== Asignación en masa ==== */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol_id',
        'photo',

        'is_active',      // ✔ flag activo/inactivo
        'is_loading',     // ✔ flag proceso de carga
        'last_login_at',  // ✔ última conexión
    ];

    /* ==== Casts ==== */
    protected $casts = [
        'is_active'     => 'boolean',
        'is_loading'    => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /* ==== Valores por defecto en documentos nuevos ==== */
    protected $attributes = [
        'is_active'  => true,
        'is_loading' => false,
         'last_login_at'  => null,
    ];

    /* ==== Accessors para evitar null ==== */
    public function getIsActiveAttribute($value)   { return $value ?? false; }
    public function getIsLoadingAttribute($value)  { return $value ?? false; }
    public function getLastLoginAtAttribute($value)
    {
        return $value ? $this->asDateTime($value) : null;
    }

    // Accessor para el avatar
    public function getAvatarUrlAttribute()
    {
        if ($this->photo && file_exists(storage_path('app/public/' . $this->photo))) {
            return asset('storage/' . $this->photo);
        }
        return asset('img/avatar.png');
    }

    /* ==== JWT ==== */
    public function getJWTIdentifier()   { return $this->getKey(); }
    public function getJWTCustomClaims() { return []; }

    /* ==== Notificaciones vía Mongo ==== */
    public function notifications()
    {
        return $this->morphMany(
            \App\Models\DatabaseNotification::class,
            'notifiable'
        )->orderBy('created_at', 'desc');
    }

    public function routeNotificationForDatabase($notification)
    {
        return (new \App\Models\DatabaseNotification)
               ->setConnection('mongodb');
    }
}

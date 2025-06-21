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

    protected $connection = 'mongodb';
    protected $collection = 'users'; // Tu colección en MongoDB

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol_id',
        'photo',
        'is_active',
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // === JWT Required Methods ===

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Usualmente es el _id
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}

<?php

namespace App\Models;

use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Eloquent implements AuthenticatableContract, JWTSubject
{
    use Authenticatable;

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

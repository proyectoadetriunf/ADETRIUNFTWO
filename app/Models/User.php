<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Tymon\JWTAuth\Contracts\JWTSubject;

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

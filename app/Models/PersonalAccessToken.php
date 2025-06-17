<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Laravel\Sanctum\Contracts\HasAbilities;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class PersonalAccessToken extends SanctumPersonalAccessToken implements HasAbilities
{
    use HybridRelations;

    protected $connection = 'mongodb';
    protected $collection = 'personal_access_tokens';

    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
        'abilities',
        'last_used_at',
    ];

    protected $casts = [
        'abilities' => 'array',
        'last_used_at' => 'datetime',
    ];

    public function tokenable()
    {
        return $this->morphTo();
    }

    public function can($ability)
    {
        return in_array('*', $this->abilities) || in_array($ability, $this->abilities);
    }

    public function cant($ability)
    {
        return ! $this->can($ability);
    }
}

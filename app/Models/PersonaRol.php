<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class PersonaRol extends Model
{
    use HasFactory;

    protected $table = 'persona_roles'; 
    protected $primaryKey = 'id'; 
    protected $fillable = [
        'persona_id',
        'rol_id',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
}

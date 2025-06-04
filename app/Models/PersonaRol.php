<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonaRol extends Model
{
    use HasFactory;

    protected $table = 'persona_roles'; // Asegúrate que este sea el nombre de tu tabla real
    protected $primaryKey = 'id'; // Cambia si tu PK tiene otro nombre

    protected $fillable = [
        'persona_id',
        'rol_id',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
}

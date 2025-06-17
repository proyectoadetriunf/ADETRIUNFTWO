<?php

namespace App\Models;


use Jenssegers\Mongodb\Eloquent\Model;


class Departamento extends Model
{
    protected $table = 'departamentos';
    protected $primaryKey = 'departamento_id';
    public $timestamps = false;

}

<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Recibo extends Model
{
    protected $table = 'recibos';
    protected $primaryKey = 'recibo_id';
    public $timestamps = false;

    protected $fillable = [
        'donante_id',
        'beneficiario_id',
        'monto_recibo',
        'metodo_pago_recibo',
        'referencia_pago',
        'fecha_recibo'
    ];
}

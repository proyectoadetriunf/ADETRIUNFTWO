<?php

// app/Models/Archivo.php
class Archivo extends \Jenssegers\Mongodb\Eloquent\Model
{
    protected $connection = 'mongodb';
    protected $collection = 'archivos';

    protected $fillable = [
        'ref_type','ref_id','nombre_original','ruta','mime','tamaño',
        'disk','categoria','titulo','descripcion','subido_por'
    ];

    public function archivoable()        // relación polimórfica
    {
        return $this->morphTo(null, 'ref_type', 'ref_id');
    }
}

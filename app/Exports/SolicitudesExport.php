<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class SolicitudesExport implements FromArray
{
    protected $solicitudes;

    public function __construct(array $solicitudes)
    {
        $this->solicitudes = $solicitudes;
    }

    public function array(): array
    {
        return $this->solicitudes;
    }
}

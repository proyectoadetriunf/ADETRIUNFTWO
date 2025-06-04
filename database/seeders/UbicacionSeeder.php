<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UbicacionSeeder extends Seeder
{
    public function run()
    {
        // Cargar departamentos
        $json = File::get(database_path('data/departamentos.json'));
        $departamentos = json_decode($json);
        foreach ($departamentos as $dep) {
            DB::table('departamentos')->insert([
                'departamento_id' => $dep->departamento_id,
                'nombre_depto' => $dep->nombre_depto,
            ]);
        }

        // Cargar municipios
        $json = File::get(database_path('data/municipios.json'));
        $municipios = json_decode($json);
        foreach ($municipios as $mun) {
            DB::table('municipios')->insert([
                'municipio_id' => $mun->municipio_id,
                'nombre_muni' => $mun->nombre_muni,
                'departamento_id' => $mun->departamento_id,
            ]);
        }
    }
}



<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UbicacionSeeder extends Seeder
{   
    public function run()
    {     
        $this->call(UbicacionSeeder::class);

        // Leer y decodificar el archivo departamentos.json
        $json = File::get(database_path('data/departamentos.json'));
        $data = json_decode($json);

        foreach ($data as $obj) {
            DB::table('departamentos')->insert([
                'departamento_id' => $obj->id,
                'nombre_depto' => $obj->nombre,
            ]);
        }

        // Repetir el proceso para municipios.json
        $json = File::get(database_path('data/municipios.json'));
        $data = json_decode($json);

        foreach ($data as $obj) {
            DB::table('municipios')->insert([
                'municipio_id' => $obj->id,
                'nombre_muni' => $obj->nombre,
                'departamento_id' => $obj->departamento_id,
            ]);
        }
    }
}

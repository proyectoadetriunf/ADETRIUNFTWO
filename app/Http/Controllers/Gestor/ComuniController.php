<?php

namespace App\Http\Controllers\gestor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComuniController extends Controller
{
    public function index()
    {
        // Contar beneficiarios
        $beneficiarios = DB::connection('mongodb')
            ->collection('beneficiarios')
            ->count();

        // Obtener proyectos desde MongoDB
        $proyectosRaw = DB::connection('mongodb')
            ->collection('proyectos')
            ->get();

        // Convertir a colección para usar filter y map
        $proyectos = collect($proyectosRaw)->map(function ($proyecto) {
            // Obtener seguimientos del proyecto
            $seguimientos = DB::connection('mongodb')
                ->collection('seguimientos')
                ->where('proyecto_id', $proyecto['_id'])
                ->get();

            // Calcular avance total
            $totalAvance = 0;
            foreach ($seguimientos as $s) {
                $totalAvance += $s['avance'] ?? 0;
            }

            // Agregar coordenadas simuladas si no existen
            $proyecto['lat'] = $proyecto['lat'] ?? fake()->randomFloat(6, 13.095, 13.105);
            $proyecto['lng'] = $proyecto['lng'] ?? fake()->randomFloat(6, -87.030, -87.020);

            // Agregar avance total
            $proyecto['avance_total'] = $totalAvance;

            return $proyecto;
        });

        // Contar proyectos activos (con nombre lleno)
        $proyectosActivos = $proyectos->filter(function ($proyecto) {
            return !empty($proyecto['nombre']);
        })->count();

        // Enviar datos a la vista
        return view('gestor.comuni.index', [
            'beneficiarios' => $beneficiarios,
            'proyectos' => $proyectos,
            'proyectosActivos' => $proyectosActivos,
        ]);
    }
}


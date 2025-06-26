<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ResumenController extends Controller
{
    public function index()
    {
        $proyectos = DB::connection('mongodb')->collection('proyectos')->get();
        $totalProyectos = $proyectos->count();

        $finalizados = $proyectos->where('estado', 'Finalizado')->count();
        $enProgreso = $totalProyectos - $finalizados;

        $avanceTotal = $totalProyectos > 0
            ? $proyectos->avg('porcentaje') ?? 0
            : 0;

        $datosGrafica = $proyectos->map(function ($p) {
            return [
                'nombre' => $p['nombre'] ?? 'Sin Nombre',
                'avance' => $p['porcentaje'] ?? 0,
            ];
        });

        $totalBeneficiarios = DB::connection('mongodb')->collection('beneficiarios')->count();
        $totalCitas = DB::connection('mongodb')->collection('citas')->count();

        return view('gestor.resumen.index', compact(
            'totalProyectos',
            'finalizados',
            'enProgreso',
            'totalBeneficiarios',
            'totalCitas',
            'avanceTotal',
            'datosGrafica'
        ));
    }
}

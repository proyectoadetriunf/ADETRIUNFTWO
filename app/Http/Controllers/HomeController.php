<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Proyecto;
use App\Models\Persona;
use App\Models\Solicitud;
use App\Models\Recibo;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalProyectos = Proyecto::count();
        $totalBeneficiarios = Persona::join('persona_roles', 'personas.persona_id', '=', 'persona_roles.persona_id')
            ->where('persona_roles.rol_id', 3)
            ->count();
        $totalDonaciones = Recibo::count();
        $totalSolicitudesPendientes = Solicitud::where('estado', 'pendiente')->count();
        $proyectosSQL = Proyecto::orderBy('created_at', 'desc')->take(5)->get();

        $beneficiarios = DB::connection('mongodb')
            ->collection('beneficiarios')
            ->count();

        $proyectosRaw = DB::connection('mongodb')
            ->collection('proyectos')
            ->get();

        $proyectos = collect($proyectosRaw)->map(function ($proyecto) {
            $seguimientos = DB::connection('mongodb')
                ->collection('seguimientos')
                ->where('proyecto_id', $proyecto['_id'])
                ->get();

            $totalAvance = 0;
            foreach ($seguimientos as $s) {
                $totalAvance += $s['avance'] ?? 0;
            }

            // Coordenadas fijas por defecto si no existen
            $proyecto['lat'] = $proyecto['lat'] ?? 13.1006;
            $proyecto['lng'] = $proyecto['lng'] ?? -87.025;
            $proyecto['avance_total'] = $totalAvance;

            return $proyecto;
        });

        $proyectosActivos = $proyectos->filter(function ($proyecto) {
            return !empty($proyecto['nombre']);
        })->count();

        return view('home', compact(
            'totalProyectos',
            'totalBeneficiarios',
            'totalDonaciones',
            'totalSolicitudesPendientes',
            'proyectos',
            'beneficiarios',
            'proyectosActivos'
        ));
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $proyectos = Proyecto::orderBy('created_at', 'desc')->take(5)->get(); // 🔹 Esta línea faltaba

        return view('home', compact(
            'totalProyectos',
            'totalBeneficiarios',
            'totalDonaciones',
            'totalSolicitudesPendientes',
            'proyectos'
        ));
    }
    
    public function mostrarMapa()
{
    $proyectos = DB::connection('mongodb')->collection('proyectos')->get(['nombre', 'lat', 'lng', 'departamento']);
    return view('mapa', compact('proyectos'));
}

}


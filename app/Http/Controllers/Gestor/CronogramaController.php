<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectId;

class CronogramaController extends Controller
{
    public function index($proyecto_id)
    {
        $proyecto = DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($proyecto_id))->first();

        $actividades = $proyecto['cronograma'] ?? [];

        return view('gestor.asignados.cronograma', [
            'proyecto_id' => $proyecto_id,
            'actividades' => $actividades
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'proyecto_id' => 'required',
            'actividad'   => 'required|string|max:255',
            'inicio'      => 'required|date',
            'fin'         => 'required|date|after_or_equal:inicio',
        ]);

        $nuevaActividad = [
            '_id'       => new ObjectId(),
            'actividad' => $request->actividad,
            'inicio'    => $request->inicio,
            'fin'       => $request->fin,
            'completado'=> false,
        ];

        DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($request->proyecto_id))
            ->push('cronograma', $nuevaActividad);

        return redirect()->route('gestor.proyectos.cronograma', $request->proyecto_id)
                         ->with('success', 'Actividad agregada al cronograma.');
    }

    public function finalizar($actividad_id)
    {
        $proyecto = DB::connection('mongodb')->collection('proyectos')->where('cronograma._id', new ObjectId($actividad_id))->first();

        if (!$proyecto) {
            return redirect()->back()->with('error', 'Actividad no encontrada.');
        }

        $cronograma = $proyecto['cronograma'];

        foreach ($cronograma as &$actividad) {
            if ((string)$actividad['_id'] === $actividad_id) {
                $actividad['completado'] = true;
                break;
            }
        }

        DB::connection('mongodb')->collection('proyectos')
            ->where('_id', $proyecto['_id'])
            ->update(['cronograma' => $cronograma]);

        return redirect()->route('gestor.proyectos.cronograma', (string) $proyecto['_id'])
                         ->with('success', 'Actividad finalizada.');
    }
}

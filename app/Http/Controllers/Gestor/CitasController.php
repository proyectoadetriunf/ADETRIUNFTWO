<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectId;

class CitasController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'programadas');

        // Cargar proyectos
        $proyectos = DB::connection('mongodb')->collection('proyectos')->get();

        // Cargar citas
        $citas = DB::connection('mongodb')->collection('citas')->get();

        // Preparar eventos para el calendario
        $citasCalendar = $citas->map(function ($cita) use ($proyectos) {
            $nombreProyecto = 'Sin nombre';
            if (isset($cita['proyecto_id'])) {
                $proyecto = $proyectos->first(function ($p) use ($cita) {
                    return isset($p['_id']) && (string) $p['_id'] === (string) $cita['proyecto_id'];
                });

                if ($proyecto) {
                    $nombreProyecto = $proyecto['nombre'] ?? 'Sin nombre';
                }
            }

            return [
                'id'    => (string) ($cita['_id'] ?? ''),
                'title' => $nombreProyecto . ' - ' . ($cita['motivo'] ?? 'Sin motivo'),
                'start' => $cita['fecha'] ?? now()->toDateString(),
                'color' => '#007bff',
            ];
        });

        return view('gestor.citas.index', [
            'tab' => $tab,
            'proyectos' => $proyectos,
            'citasCalendar' => $citasCalendar,
        ]);
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'proyecto_id' => 'required|string',
            'fecha'       => 'required|date',
            'motivo'      => 'required|string',
            'archivo'     => 'nullable|file|max:10240'
        ]);

        $data = [
            'proyecto_id' => new ObjectId($request->proyecto_id),
            'fecha'       => $request->fecha,
            'motivo'      => $request->motivo,
            'created_at'  => now()
        ];

        if ($request->hasFile('archivo')) {
            $path = $request->file('archivo')->store('citas', 'public');
            $data['archivo'] = $path;
        }

        DB::connection('mongodb')->collection('citas')->insert($data);

        return redirect()->route('gestor.citas.index', ['tab' => 'programadas'])
                         ->with('success', 'Cita registrada correctamente.');
    }
}

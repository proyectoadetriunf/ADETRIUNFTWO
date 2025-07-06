<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectId;

class ProyectoController extends Controller
{
    public function index(Request $request)
    {
        $proyectosRaw = DB::connection('mongodb')->collection('proyectos')->get();

        $proyectos = $proyectosRaw->map(function ($item) {
            return [
                '_id'           => (string) ($item['_id'] ?? ''),
                'nombre'        => $item['nombre'] ?? '',
                'descripcion'   => $item['descripcion'] ?? '',
                'anio'          => $item['anio'] ?? '',
                'estado'        => $item['estado'] ?? '',
                'seguimientos'  => $item['seguimientos'] ?? [],
                'evidencias'    => $item['evidencias'] ?? [],
            ];
        });

        $tab = $request->get('tab', 'crear');
        $proyectoSeleccionado = null;
        $seguimientos = [];

        if ($tab === 'seguimiento' && $request->has('id')) {
            $id = $request->get('id');
            $proyecto = DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($id))->first();

            if ($proyecto) {
                $proyectoSeleccionado = [
                    '_id'          => (string) $proyecto['_id'],
                    'nombre'       => $proyecto['nombre'] ?? '',
                    'descripcion'  => $proyecto['descripcion'] ?? '',
                    'anio'         => $proyecto['anio'] ?? '',
                    'estado'       => $proyecto['estado'] ?? '',
                ];

                $seguimientos = $proyecto['seguimientos'] ?? [];
            }
        }

        $proyectosNoAsignados = DB::connection('mongodb')->collection('proyectos')->whereNull('moderador_id')->get();
        $moderadores = DB::connection('mongodb')->collection('users')->where('rol_id', 2)->get();

        $asignaciones = DB::connection('mongodb')->collection('proyectos')
            ->whereNotNull('moderador_id')
            ->get()
            ->map(function ($p) {
                $moderador = DB::connection('mongodb')->collection('users')->find($p['moderador_id']);
                return [
                    'proyecto_nombre'    => $p['nombre'] ?? '',
                    'fecha_asignacion'   => $p['fecha_asignacion'] ?? now(),
                    'moderador_nombre'   => $moderador['name'] ?? 'Sin nombre'
                ];
            });

        return view('gestor.proyectos.index', compact(
            'proyectos',
            'tab',
            'proyectoSeleccionado',
            'seguimientos',
            'proyectosNoAsignados',
            'moderadores',
            'asignaciones'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'anio'        => 'required|integer|min:2000|max:2100',
            'estado'      => 'required|in:En planificación,En progreso,Finalizado'
        ]);

        DB::connection('mongodb')->collection('proyectos')->insert([
            'nombre'        => $request->nombre,
            'descripcion'   => $request->descripcion,
            'anio'          => (int) $request->anio,
            'estado'        => $request->estado,
            'created_at'    => now(),
            'seguimientos'  => [],
            'evidencias'    => []
        ]);

        return redirect()->route('gestor.proyectos.index', ['tab' => 'ver'])
                         ->with('success', 'Proyecto creado correctamente.');
    }

    public function asignar(Request $request)
    {
        $request->validate([
            'proyecto_id' => 'required',
            'moderador_id' => 'required',
        ]);

        DB::connection('mongodb')->collection('proyectos')
            ->where('_id', new ObjectId($request->proyecto_id))
            ->update([
                'moderador_id' => new ObjectId($request->moderador_id),
                'fecha_asignacion' => now()
            ]);

        return redirect()->route('gestor.proyectos.index', ['tab' => 'asignar'])
                         ->with('success', 'Proyecto asignado correctamente.');
    }

    public function agregarSeguimiento(Request $request, $id)
    {
        $request->validate([
            'avance'     => 'required|integer|min:0|max:100',
            'comentario' => 'required|string|max:1000'
        ]);

        $nuevoSeguimiento = [
            'avance'     => $request->avance,
            'comentario' => $request->comentario,
            'fecha'      => now()->toDateTimeString()
        ];

        DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($id))
            ->push('seguimientos', $nuevoSeguimiento);

        $proyecto = DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($id))->first();
        $total = 0;
        $count = 0;

        foreach ($proyecto['seguimientos'] ?? [] as $s) {
            $total += $s['avance'] ?? 0;
            $count++;
        }

        $promedio = $count > 0 ? intval($total / $count) : 0;

        if ($promedio >= 100) {
            DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($id))
                ->update(['estado' => 'Finalizado']);
        }

        return redirect()->route('gestor.proyectos.index', ['tab' => 'seguimiento', 'id' => $id])
                         ->with('success', 'Seguimiento agregado correctamente.');
    }

    public function guardarEvidencia(Request $request)
    {
        $request->validate([
            'proyecto_id' => 'required',
            'archivo'     => 'required|file|mimes:pdf,jpg,jpeg,png,docx,xlsx',
            'avance'      => 'required|integer|min:1|max:100',
            'comentario'  => 'required|string|max:1000'
        ]);

        $archivo = $request->file('archivo');
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
        $ruta = $archivo->storeAs('public/evidencias', $nombreArchivo);

        DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($request->proyecto_id))
            ->push('evidencias', [
                'archivo' => $nombreArchivo,
                'comentario' => $request->comentario,
                'fecha' => now()->toDateTimeString()
            ]);

        $this->agregarSeguimiento($request, $request->proyecto_id);

        return redirect()->route('gestor.proyectos.index', ['tab' => 'evidencias'])
                         ->with('success', 'Evidencia y avance guardados correctamente.');
    }

    public function crear()       { return redirect()->route('gestor.proyectos.index', ['tab' => 'crear']); }
    public function seguimiento() { return redirect()->route('gestor.proyectos.index', ['tab' => 'seguimiento']); }
    public function evidencias()  { return redirect()->route('gestor.proyectos.index', ['tab' => 'evidencias']); }
}

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
        // Traer todos los proyectos
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

        // Moderadores
        $moderadoresRaw = DB::connection('mongodb')
            ->collection('users')
            ->where('rol_id', 'moderador')
            ->get();

        $moderadores = collect($moderadoresRaw)->map(function($mod){
            return [
                '_id'   => (string) $mod['_id'],
                'name'  => $mod['name'] ?? '',
                'email' => $mod['email'] ?? '',
            ];
        });

        // Asignaciones
        $asignacionesRaw = DB::connection('mongodb')->collection('asignaciones')->get();

        $proyectosAsignadosIds = $asignacionesRaw->pluck('proyecto_id')->map(function($id){
            return (string) $id;
        })->toArray();

        $asignaciones = $asignacionesRaw->map(function ($asig) use ($proyectos, $moderadores) {
            $proyecto = $proyectos->firstWhere('_id', (string)$asig['proyecto_id']);
            $moderador = $moderadores->firstWhere('_id', (string)$asig['moderador_id']);

            return [
                'proyecto_nombre'   => $proyecto['nombre'] ?? 'Desconocido',
                'moderador_nombre'  => $moderador['name'] ?? 'Desconocido',
                'fecha_asignacion'  => $asig['fecha_asignacion'] ?? null,
            ];
        });

        // Filtrar proyectos que no estén asignados
        $proyectosNoAsignados = $proyectos->filter(function($proy) use ($proyectosAsignadosIds){
            return !in_array($proy['_id'], $proyectosAsignadosIds);
        });

        $tab = $request->get('tab', 'crear');
        $proyectoSeleccionado = null;
        $seguimientos = [];

        if ($tab === 'seguimiento' && $request->has('id')) {
            $id = $request->get('id');
            $proyecto = DB::connection('mongodb')
                ->collection('proyectos')
                ->where('_id', new ObjectId($id))
                ->first();

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

        return view('gestor.proyectos.index', compact(
            'proyectos',
            'proyectosNoAsignados',
            'tab',
            'proyectoSeleccionado',
            'seguimientos',
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
            'estado'      => 'required|in:Planificación,Aprobado'
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
            'proyecto_id'  => 'required',
            'moderador_id' => 'required',
        ]);

        // Verificar si ya está asignado
        $existe = DB::connection('mongodb')->collection('asignaciones')
            ->where('proyecto_id', new ObjectId($request->proyecto_id))
            ->exists();

        if ($existe) {
            return redirect()->route('gestor.proyectos.index', ['tab' => 'asignar'])
                ->with('error', 'Este proyecto ya está asignado.');
        }

        DB::connection('mongodb')->collection('asignaciones')->insert([
            'proyecto_id'      => new ObjectId($request->proyecto_id),
            'moderador_id'     => new ObjectId($request->moderador_id),
            'fecha_asignacion' => now(),
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
        $archivo->storeAs('public/evidencias', $nombreArchivo);

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

    public function crear()
    {
        return redirect()->route('gestor.proyectos.index', ['tab' => 'crear']);
    }

    public function seguimiento()
    {
        return redirect()->route('gestor.proyectos.index', ['tab' => 'seguimiento']);
    }

    public function evidencias()
    {
        return redirect()->route('gestor.proyectos.index', ['tab' => 'evidencias']);
    }
}

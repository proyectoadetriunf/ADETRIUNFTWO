<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use MongoDB\BSON\ObjectId;

class ProyectoController extends Controller
{
    public function index(Request $request)
    {
        $proyectosRaw = DB::connection('mongodb')->collection('proyectos')->get();
        $proyectosIds = $proyectosRaw->pluck('_id')->map(fn($id) => (string) $id)->toArray();

        $proyectos = $proyectosRaw->map(function ($item) {
            return [
                '_id' => (string) ($item['_id'] ?? ''),
                'nombre' => $item['nombre'] ?? '',
                'descripcion' => $item['descripcion'] ?? '',
                'anio' => $item['anio'] ?? '',
                'costo' => $item['costo'] ?? 0,
                'estado' => $item['estado'] ?? 'Aprobado',
                'seguimientos' => $item['seguimientos'] ?? [],
                'evidencias' => $item['evidencias'] ?? [],
                'gasto_total' => $item['gasto_total'] ?? 0,
                'progreso' => $item['progreso'] ?? 0,
            ];
        });

        $moderadoresRaw = DB::connection('mongodb')->collection('users')->where('rol_id', 'moderador')->get();
        $moderadores = collect($moderadoresRaw)->map(function ($mod) {
            return [
                '_id' => (string) $mod['_id'],
                'name' => $mod['name'] ?? '',
                'email' => $mod['email'] ?? '',
            ];
        });

        $asignacionesRaw = DB::connection('mongodb')->collection('asignaciones')->get();
        $proyectosAsignadosIds = $asignacionesRaw->pluck('proyecto_id')->map(fn($id) => (string) $id)->toArray();

        $asignaciones = $asignacionesRaw->map(function ($asig) use ($proyectos, $moderadores) {
            $proyecto = $proyectos->firstWhere('_id', (string) $asig['proyecto_id']);
            $moderador = $moderadores->firstWhere('_id', (string) $asig['moderador_id']);
            return [
                'proyecto_nombre' => $proyecto['nombre'] ?? 'Desconocido',
                'moderador_nombre' => $moderador['name'] ?? 'Desconocido',
                'fecha_asignacion' => $asig['fecha_asignacion'] ?? null,
            ];
        });

        $proyectosNoAsignados = $proyectos->filter(fn($proy) => !in_array($proy['_id'], $proyectosAsignadosIds));
        $tab = $request->get('tab', 'crear');

        return view('gestor.proyectos.index', compact(
            'proyectos',
            'proyectosNoAsignados',
            'tab',
            'moderadores',
            'asignaciones',
            'proyectosAsignadosIds'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'anio' => 'required|integer|min:2000|max:2100',
            'estado' => 'required|string',
            'costo' => 'required|numeric|min:0',
        ]);

        DB::connection('mongodb')->collection('proyectos')->insert([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'anio' => (int) $request->anio,
            'estado' => $request->estado,
            'costo' => (float) $request->costo,
            'seguimientos' => [],
            'evidencias' => [],
            'gasto_total' => 0,
            'progreso' => 0,
            'created_at' => now()
        ]);

        return redirect()->route('gestor.proyectos.index', ['tab' => 'ver'])->with('success', 'Proyecto guardado correctamente.');
    }

    public function asignar(Request $request)
    {
        $request->validate([
            'proyecto_id' => 'required|string',
            'moderador_id' => 'required|string',
        ]);

        $existe = DB::connection('mongodb')->collection('asignaciones')->where([
            'proyecto_id' => $request->proyecto_id,
        ])->exists();

        if ($existe) {
            return redirect()->route('gestor.proyectos.index', ['tab' => 'asignar'])->with('error', 'Este proyecto ya ha sido asignado.');
        }

        DB::connection('mongodb')->collection('asignaciones')->insert([
            'proyecto_id' => $request->proyecto_id,
            'moderador_id' => $request->moderador_id,
            'fecha_asignacion' => now(),
        ]);

        DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($request->proyecto_id))->update([
            'tecnico_asignado' => $request->moderador_id,
        ]);

        return redirect()->route('gestor.proyectos.index', ['tab' => 'asignar'])->with('success', 'Proyecto asignado correctamente.');
    }

    public function eliminar($id)
    {
        DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($id))->delete();
        DB::connection('mongodb')->collection('asignaciones')->where('proyecto_id', new ObjectId($id))->delete();

        return redirect()->route('gestor.proyectos.index', ['tab' => 'ver'])->with('success', 'Proyecto y asignaciones eliminados.');
    }

    public function editar($id)
    {
        $proyecto = DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($id))->first();

        if (!$proyecto) {
            return redirect()->back()->with('error', 'Proyecto no encontrado.');
        }

        return view('gestor.proyectos.editar', compact('proyecto'));
    }

    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'anio' => 'required|integer|min:2000|max:2100',
            'estado' => 'required|string',
            'costo' => 'required|numeric|min:0',
        ]);

        DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($id))->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'anio' => (int) $request->anio,
            'estado' => $request->estado,
            'costo' => (float) $request->costo,
        ]);

        return redirect()->route('gestor.proyectos.index', ['tab' => 'ver'])->with('success', 'Proyecto actualizado correctamente.');
    }

    public function asignados()
    {
        $userId = auth()->id();

        $asignaciones = DB::connection('mongodb')->collection('asignaciones')->where('moderador_id', $userId)->get();
        $proyectosIds = $asignaciones->pluck('proyecto_id')->map(fn($id) => (string) $id)->toArray();

        $proyectosRaw = DB::connection('mongodb')->collection('proyectos')
            ->whereIn('_id', array_map(fn($id) => new ObjectId($id), $proyectosIds))
            ->get();

        $proyectosAsignados = $proyectosRaw->map(function ($item) {
            return [
                '_id' => (string) ($item['_id'] ?? ''),
                'nombre' => $item['nombre'] ?? '',
                'descripcion' => $item['descripcion'] ?? '',
                'anio' => $item['anio'] ?? '',
                'costo' => $item['costo'] ?? 0,
                'estado' => $item['estado'] ?? 'Aprobado',
                'progreso' => $item['progreso'] ?? 0,
                'gasto_total' => $item['gasto_total'] ?? 0,
            ];
        });

        return view('gestor.asignados.index', compact('proyectosAsignados'));
    }

    public function cronograma($proyecto_id)
    {
        $proyecto = DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($proyecto_id))->first();

        if (!$proyecto) {
            return redirect()->back()->with('error', 'Proyecto no encontrado.');
        }

        $actividades = $proyecto['cronograma'] ?? [];

        return view('gestor.asignados.cronograma', [
            'proyecto' => $proyecto,
            'proyecto_id' => $proyecto_id,
            'actividades' => $actividades,
        ]);
    }

    public function avances($id)
    {
        $proyecto = DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($id))->first();

        if (!$proyecto) {
            return redirect()->back()->with('error', 'Proyecto no encontrado.');
        }

        return view('gestor.asignados.avances', compact('proyecto'));
    }

    public function guardarAvance(Request $request)
    {
        $request->validate([
            'proyecto_id' => 'required|string',
            'descripcion' => 'required|string',
            'monto_gasto' => 'nullable|numeric|min:0',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $proyectoId = $request->proyecto_id;

        $avance = [
            'descripcion' => $request->descripcion,
            'fecha' => now(),
            'monto_gasto' => $request->monto_gasto ?? 0,
        ];

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('public/evidencias');
            $avance['imagen'] = $path;
        }

        DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($proyectoId))->push('evidencias', $avance);

        DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($proyectoId))->increment('gasto_total', $avance['monto_gasto']);

        return back()->with('success', 'Avance guardado correctamente.');
    }

    public function actualizarProgreso(Request $request, $id)
    {
        $progreso = (int) $request->input('progreso');
        $estado = $progreso >= 100 ? 'Finalizado' : 'En Progreso';

        DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($id))->update([
            'progreso' => $progreso,
            'estado' => $estado,
        ]);

        return back()->with('success', 'Progreso actualizado correctamente.');
    }
}

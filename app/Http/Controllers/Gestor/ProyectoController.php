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

        $moderadoresRaw = DB::connection('mongodb')
            ->collection('users')
            ->where('rol_id', 'moderador')
            ->get();

        $moderadores = collect($moderadoresRaw)->map(function ($mod) {
            return [
                '_id'   => (string) $mod['_id'],
                'name'  => $mod['name'] ?? '',
                'email' => $mod['email'] ?? '',
            ];
        });

        $asignacionesRaw = DB::connection('mongodb')->collection('asignaciones')->get();

        $proyectosAsignadosIds = $asignacionesRaw->pluck('proyecto_id')->map(fn($id) => (string) $id)->toArray();

        $asignaciones = $asignacionesRaw->map(function ($asig) use ($proyectos, $moderadores) {
            $proyecto = $proyectos->firstWhere('_id', (string)$asig['proyecto_id']);
            $moderador = $moderadores->firstWhere('_id', (string)$asig['moderador_id']);

            return [
                'proyecto_nombre'   => $proyecto['nombre'] ?? 'Desconocido',
                'moderador_nombre'  => $moderador['name'] ?? 'Desconocido',
                'fecha_asignacion'  => $asig['fecha_asignacion'] ?? null,
            ];
        });

        $proyectosNoAsignados = $proyectos->filter(fn($proy) => !in_array($proy['_id'], $proyectosAsignadosIds));

        $tab = $request->get('tab', 'crear');
        $proyectoSeleccionado = null;
        $seguimientos = [];

        if ($tab === 'seguimiento' && $request->has('id')) {
            $id = $request->get('id');
            $proyecto = DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($id))->first();

            if ($proyecto) {
                $proyectoSeleccionado = [
                    '_id'         => (string) $proyecto['_id'],
                    'nombre'      => $proyecto['nombre'] ?? '',
                    'descripcion' => $proyecto['descripcion'] ?? '',
                    'anio'        => $proyecto['anio'] ?? '',
                    'estado'      => $proyecto['estado'] ?? '',
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

        $existe = DB::connection('mongodb')->collection('asignaciones')
            ->where('proyecto_id', new ObjectId($request->proyecto_id))
            ->exists();

        if ($existe) {
            return redirect()->route('gestor.proyectos.index', ['tab' => 'asignar'])
                ->with('error', 'Este proyecto ya está asignado.');
        }

        DB::connection('mongodb')->collection('asignaciones')->insert([
            'proyecto_id'      => (string) $request->proyecto_id,
            'moderador_id'     => (string) $request->moderador_id,
            'fecha_asignacion' => now(),
        ]);

        $proyecto = DB::connection('mongodb')->collection('proyectos')->where('_id', new ObjectId($request->proyecto_id))->first();
        $mensaje = 'Se te ha asignado el proyecto: ' . ($proyecto['nombre'] ?? 'Proyecto');

        \App\Models\NotificacionPersonalizada::create([
            'user_id' => $request->moderador_id,
            'mensaje' => $mensaje,
            'leida' => false,
            'created_at' => now(),
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

    public function crear()       { return redirect()->route('gestor.proyectos.index', ['tab' => 'crear']); }
    public function seguimiento() { return redirect()->route('gestor.proyectos.index', ['tab' => 'seguimiento']); }
    public function evidencias()  { return redirect()->route('gestor.proyectos.index', ['tab' => 'evidencias']); }

    public function asignados()
    {
        $user = auth()->user();
        $asignaciones = DB::connection('mongodb')->collection('asignaciones')
            ->where('moderador_id', (string) $user->_id)
            ->get();

        $proyectos = collect();
        foreach ($asignaciones as $asig) {
            $proy = DB::connection('mongodb')->collection('proyectos')->where('_id', $asig['proyecto_id'])->first();
            if ($proy) {
                $proy['_id'] = (string) $proy['_id'];
                $proy['evidencias'] = $proy['evidencias'] ?? [];
                $proyectos->push($proy);
            }
        }

        return view('gestor.asignados', ['proyectos' => $proyectos]);
    }

    public function subirEvidencia(Request $request, $proyectoId)
    {
        $request->validate([
            'evidencia' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $file = $request->file('evidencia');
        $nombreOriginal = $file->getClientOriginalName();
        $archivo = uniqid() . '_' . $nombreOriginal;
        $file->storeAs('evidencias', $archivo);

        $proyecto = DB::connection('mongodb')->collection('proyectos')->where('_id', $proyectoId);
        $proy = $proyecto->first();
        $evidencias = $proy['evidencias'] ?? [];

        $evidencias[] = [
            'nombre' => $nombreOriginal,
            'archivo' => $archivo,
            'fecha' => now(),
        ];

        $proyecto->update(['evidencias' => $evidencias]);

        return back()->with('success', 'Evidencia subida correctamente.');
    }

    public function descargarEvidencia($proyectoId, $archivo)
    {
        $ruta = storage_path('app/evidencias/' . $archivo);
        if (!file_exists($ruta)) {
            abort(404);
        }
        return response()->download($ruta);
    }

    public function eliminarEvidencia($proyectoId, $archivo)
    {
        $proyecto = DB::connection('mongodb')->collection('proyectos')->where('_id', $proyectoId);
        $proy = $proyecto->first();

        if (!$proy) {
            return back()->with('error', 'Proyecto no encontrado.');
        }

        $evidencias = array_filter($proy['evidencias'] ?? [], fn($ev) => $ev['archivo'] !== $archivo);
        $proyecto->update(['evidencias' => array_values($evidencias)]);

        $ruta = storage_path('app/evidencias/' . $archivo);
        if (file_exists($ruta)) {
            @unlink($ruta);
        }

        return back()->with('success', 'Evidencia eliminada correctamente.');
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MongoDB\BSON\ObjectId;
use App\Models\NotificacionPersonalizada;
use Carbon\Carbon;

class TareaController extends Controller
{
    public function index()
    {
        // Obtener todos los proyectos
        $proyectos = DB::connection('mongodb')->collection('proyectos')->get();

        // Obtener todos los moderadores y técnicos (roles como strings)
        $moderadores = DB::connection('mongodb')->collection('users')
            ->whereIn('rol_id', ['moderador', 'tecnico'])
            ->get();

        // Obtener todas las tareas
        $tareasRaw = DB::connection('mongodb')->collection('tareas')->get();

        // Formatear tareas con nombres de proyecto y moderador
        $tareas = $tareasRaw->map(function ($item) use ($proyectos, $moderadores) {
            $proyecto = $proyectos->first(function ($p) use ($item) {
                return isset($p['_id']) && (string) $p['_id'] === (string) $item['proyecto_id'];
            });

            $moderador = $moderadores->first(function ($m) use ($item) {
                return isset($item['moderador_id']) && (string) $m['_id'] === (string) $item['moderador_id'];
            });

            // Formatear fecha de creación en formato hondureño
            $fechaCreacion = '';
            if (isset($item['fecha_hora_creacion'])) {
                $fechaCreacion = $item['fecha_hora_creacion'];
            } elseif (isset($item['created_at'])) {
                $fechaCreacion = \Carbon\Carbon::parse($item['created_at'])
                    ->setTimezone('America/Tegucigalpa')
                    ->format('d/m/Y h:i A');
            }

            return [
                '_id'          => (string) $item['_id'],
                'proyecto'     => $proyecto['nombre'] ?? 'Desconocido',
                'titulo'       => $item['titulo'] ?? '',
                'descripcion'  => $item['descripcion'] ?? '',
                'etapa'        => $item['etapa'] ?? '',
                'moderador'    => $moderador['name'] ?? 'No asignado',
                'estado'       => $item['estado'] ?? 'Pendiente',
                'fecha_inicio' => $item['fecha_inicio'] ?? '',
                'fecha_fin'    => $item['fecha_fin'] ?? '',
                'created_at'   => $item['created_at'] ?? '',
                'fecha_hora_creacion' => $fechaCreacion,
            ];
        });

        return view('admin.tareas.index', compact('tareas', 'proyectos', 'moderadores'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'proyecto_id'  => 'required',
            'titulo'       => 'required|string|max:255',
            'descripcion'  => 'required|string',
            'etapa'        => 'required|string',
            'moderador_id' => 'required',
            'estado'       => 'required|in:Pendiente,En proceso,Completada',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio'
        ]);

        // Obtener información del proyecto y moderador para la notificación
        $proyecto = DB::connection('mongodb')->collection('proyectos')
            ->where('_id', new ObjectId($request->proyecto_id))
            ->first();

        $moderador = DB::connection('mongodb')->collection('users')
            ->where('_id', new ObjectId($request->moderador_id))
            ->first();

        // Guardar la tarea
        $fechaHoraCreacion = now()->setTimezone('America/Tegucigalpa')->format('d/m/Y h:i A');
        
        DB::connection('mongodb')->collection('tareas')->insert([
            'proyecto_id'  => new ObjectId($request->proyecto_id),
            'titulo'       => $request->titulo,
            'descripcion'  => $request->descripcion,
            'etapa'        => $request->etapa,
            'moderador_id' => new ObjectId($request->moderador_id),
            'estado'       => $request->estado,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
            'created_at'   => now(),
            'fecha_hora_creacion' => $fechaHoraCreacion
        ]);

        // Enviar notificación al moderador asignado
        if ($moderador) {
            $mensaje = "Se te ha asignado una nueva tarea: '{$request->titulo}' en el proyecto '{$proyecto['nombre']}'. Etapa: {$request->etapa}. Fecha límite: {$request->fecha_fin}. Creada el {$fechaHoraCreacion}.";
            
            try {
                // Usar la misma conexión MongoDB directa para insertar la notificación
                DB::connection('mongodb')->collection('notificaciones_personalizadas')->insert([
                    'user_id'    => new ObjectId($request->moderador_id),
                    'mensaje'    => $mensaje,
                    'leida'      => false,
                    'created_at' => now(),
                    'fecha_hora_notificacion' => $fechaHoraCreacion
                ]);
                
                Log::info('Notificación creada para moderador ID: ' . $request->moderador_id . ' el ' . $fechaHoraCreacion);
                
            } catch (\Exception $e) {
                Log::error('Error al crear notificación: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.tareas.index')->with('success', 'Tarea registrada correctamente y notificación enviada al moderador.');
    }

    public function completar(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,En proceso,Completada'
        ]);

        DB::connection('mongodb')->collection('tareas')
            ->where('_id', new ObjectId($id))
            ->update(['estado' => $request->estado]);

        return redirect()->route('admin.tareas.index')->with('success', 'Estado actualizado.');
    }


    public function eliminar($id)
{
    DB::connection('mongodb')->collection('tareas')
        ->where('_id', new ObjectId($id))
        ->delete();

    return redirect()->route('admin.tareas.index')->with('success', 'Tarea eliminada correctamente.');
}

}

<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectId;

class TareaController extends Controller
{
    public function index()
    {
        // Obtener todos los proyectos
        $proyectos = DB::connection('mongodb')->collection('proyectos')->get();

        // Obtener todas las tareas
        $tareasRaw = DB::connection('mongodb')->collection('tareas')->get();

        // Formatear tareas
        $tareas = $tareasRaw->map(function ($item) use ($proyectos) {
            $proyecto = $proyectos->first(function ($p) use ($item) {
                return isset($p['_id']) && (string) $p['_id'] === (string) $item['proyecto_id'];
            });

            return [
                '_id'          => (string) $item['_id'],
                'proyecto'     => $proyecto['nombre'] ?? 'Desconocido',
                'titulo'       => $item['titulo'] ?? '',
                'descripcion'  => $item['descripcion'] ?? '',
                'etapa'        => $item['etapa'] ?? '',
                'estado'       => $item['estado'] ?? 'Pendiente',
                'fecha_inicio' => $item['fecha_inicio'] ?? '',
                'fecha_fin'    => $item['fecha_fin'] ?? '',
                'created_at'   => $item['created_at'] ?? '',
            ];
        });

        return view('gestor.tareas.index', compact('tareas', 'proyectos'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'proyecto_id'  => 'required',
            'titulo'       => 'required|string|max:255',
            'descripcion'  => 'required|string',
            'etapa'        => 'required|string',
            'estado'       => 'required|in:Pendiente,En proceso,Completada',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio'
        ]);

        DB::connection('mongodb')->collection('tareas')->insert([
            'proyecto_id'  => new ObjectId($request->proyecto_id),
            'titulo'       => $request->titulo,
            'descripcion'  => $request->descripcion,
            'etapa'        => $request->etapa,
            'estado'       => $request->estado,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
            'created_at'   => now()
        ]);

        return redirect()->route('gestor.tareas.index')->with('success', 'Tarea registrada correctamente.');
    }

    public function completar(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,En proceso,Completada'
        ]);

        DB::connection('mongodb')->collection('tareas')
            ->where('_id', new ObjectId($id))
            ->update(['estado' => $request->estado]);

        return redirect()->route('gestor.tareas.index')->with('success', 'Estado actualizado.');
    }
}

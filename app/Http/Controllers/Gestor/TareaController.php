<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectId;

class TareaController extends Controller
{
    public function misTareas()
    {
        $userId = Auth::user()->_id;

        $tareasRaw = DB::connection('mongodb')->collection('tareas')
            ->where('moderador_id', new ObjectId($userId))
            ->where('estado', '!=', 'Completada')
            ->get();

        $proyectos = DB::connection('mongodb')->collection('proyectos')->get();

        $tareas = $tareasRaw->map(function ($tarea) use ($proyectos) {
            $proyecto = $proyectos->first(function ($p) use ($tarea) {
                return (string) $p['_id'] === (string) $tarea['proyecto_id'];
            });

            return [
                '_id'          => (string) $tarea['_id'],
                'titulo'       => $tarea['titulo'] ?? '',
                'etapa'        => $tarea['etapa'] ?? '',
                'estado'       => $tarea['estado'] ?? '',
                'fecha_inicio' => $tarea['fecha_inicio'] ?? '',
                'fecha_fin'    => $tarea['fecha_fin'] ?? '',
                'proyecto'     => $proyecto['nombre'] ?? 'Desconocido',
            ];
        });

        return view('gestor.tareas.mis-tareas', compact('tareas'));
    }

    public function finalizarTarea(Request $request, $id)
    {
        $request->validate([
            'evidencia' => 'required|file|max:2048'
        ]);

        $filePath = $request->file('evidencia')->store('evidencias', 'public');

        DB::connection('mongodb')->collection('tareas')
            ->where('_id', new ObjectId($id))
            ->update([
                'estado' => 'Completada',
                'evidencia' => $filePath,
                'completada_at' => now()
            ]);

        return back()->with('success', 'Tarea finalizada con éxito.');
    }
}

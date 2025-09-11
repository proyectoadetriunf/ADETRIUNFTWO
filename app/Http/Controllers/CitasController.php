<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitasController extends Controller
{
    public function index()
    {
        $usuarioId = (string) auth()->user()->_id;

        // Obtener citas del técnico autenticado
        $citas = DB::connection('mongodb')
            ->collection('citas')
            ->where('tecnico_id', $usuarioId)
            ->orderBy('fecha', 'desc')
            ->get();

        // Formato para FullCalendar
        $citasCalendar = $citas->map(function ($cita) {
            return [
                'id' => (string) $cita['_id'],
                'title' => $cita['motivo'],
                'start' => $cita['fecha'],
                'color' => '#007bff',
                'usuario' => $cita['usuario'] ?? 'N/D',
            ];
        });

        return view('gestor.citas.index', [
            'citas' => $citas,
            'citasCalendar' => $citasCalendar,
        ]);
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'motivo' => 'required|string',
            'archivo' => 'nullable|file|max:2048',
        ]);

        $archivo = null;

        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo')->store('citas', 'public');
        }

        // Obtener nombre del usuario actual
        $usuario = auth()->user();
        $nombreUsuario = $usuario->name ?? $usuario->nombre ?? 'Usuario';

        DB::connection('mongodb')->collection('citas')->insert([
            'tecnico_id' => (string) $usuario->_id,
            'usuario' => $nombreUsuario,
            'fecha' => $request->fecha,
            'motivo' => $request->motivo,
            'archivo' => $archivo,
            'created_at' => now(),
        ]);

        return redirect()->route('gestor.citas.index')->with('success', '✅ Cita guardada exitosamente.');
    }
}

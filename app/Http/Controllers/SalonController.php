<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalonController extends Controller
{
    /**
     * Mostrar el calendario y formulario de reservas.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'uso');

        // Leer todas las reservas
        $reservas = DB::table('salon_reservas')->get();

        // Formato FullCalendar compatible con arrays u objetos
        $reservasCalendar = collect($reservas)->map(function ($reserva) {
            $usuario = is_array($reserva) ? $reserva['usuario'] ?? 'Desconocido' : $reserva->usuario;
            $fecha = is_array($reserva) ? $reserva['fecha'] : $reserva->fecha;
            $hora_inicio = is_array($reserva) ? $reserva['hora_inicio'] : $reserva->hora_inicio;
            $hora_fin = is_array($reserva) ? $reserva['hora_fin'] : $reserva->hora_fin;
            $motivo = is_array($reserva) ? $reserva['motivo'] : $reserva->motivo;

            return [
                'title' => $usuario,
                'start' => $fecha . 'T' . $hora_inicio,
                'end' => $fecha . 'T' . $hora_fin,
                'motivo' => $motivo,
                'usuario' => $usuario,
            ];
        });

        return view('gestor.salon.index', [
            'tab' => $tab,
            'reservasCalendar' => $reservasCalendar,
            'reservas' => $reservas,
        ]);
    }

    /**
     * Guardar una nueva reserva.
     */
    public function guardar(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'motivo' => 'required|string|max:1000',
        ]);

        $usuarioNombre = auth()->user()->name ?? 'Usuario';

        // Verificar si ya existe una reserva en ese horario
        $conflicto = DB::table('salon_reservas')
            ->where('fecha', $request->fecha)
            ->where(function ($query) use ($request) {
                $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                      ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('hora_inicio', '<', $request->hora_inicio)
                            ->where('hora_fin', '>', $request->hora_fin);
                      });
            })
            ->exists();

        if ($conflicto) {
            return redirect()->back()->withErrors(['fecha' => '⚠️ El salón ya tiene una reserva en ese horario.'])->withInput();
        }

        // Guardar en la base de datos
        DB::table('salon_reservas')->insert([
            'usuario' => $usuarioNombre,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'motivo' => $request->motivo,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('gestor.salon.index')->with('success', '✅ Reserva guardada exitosamente.');
    }
}

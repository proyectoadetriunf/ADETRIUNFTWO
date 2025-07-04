<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalonController extends Controller
{
    /**
     * Mostrar calendario y formulario.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'uso');

        // Leer reservas de la base
        $reservas = DB::table('salon_reservas')->get();

        // Formato FullCalendar (SIN id)
        $reservasCalendar = collect($reservas)->map(function ($reserva) {
            // Si viene como objeto
            if (is_object($reserva)) {
                return [
                    'title' => $reserva->empleado,
                    'start' => $reserva->fecha . 'T' . $reserva->hora_inicio,
                    'end' => $reserva->fecha . 'T' . $reserva->hora_fin,
                    'motivo' => $reserva->motivo,
                ];
            }

            // Si viene como array
            return [
                'title' => $reserva['empleado'],
                'start' => $reserva['fecha'] . 'T' . $reserva['hora_inicio'],
                'end' => $reserva['fecha'] . 'T' . $reserva['hora_fin'],
                'motivo' => $reserva['motivo'],
            ];
        });

        return view('gestor.salon.index', [
            'tab' => $tab,
            'reservasCalendar' => $reservasCalendar,
        ]);
    }

    /**
     * Guardar nueva reserva.
     */
    public function guardar(Request $request)
    {
        $request->validate([
            'empleado' => 'required|string|max:255',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'motivo' => 'required|string',
        ]);

        // Verificar conflictos
        $existe = DB::table('salon_reservas')
            ->where('fecha', $request->fecha)
            ->where(function($q) use ($request) {
                $q->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                  ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                  ->orWhere(function($q2) use ($request) {
                      $q2->where('hora_inicio', '<', $request->hora_inicio)
                         ->where('hora_fin', '>', $request->hora_fin);
                  });
            })
            ->exists();

        if ($existe) {
            return redirect()->back()
                ->withErrors(['fecha' => 'Este salón ya tiene una reserva en ese horario.'])
                ->withInput();
        }

        // Insertar la reserva
        DB::table('salon_reservas')->insert([
            'empleado' => $request->empleado,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'motivo' => $request->motivo,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('gestor.salon.index')->with('success', 'Reserva creada correctamente.');
    }
}

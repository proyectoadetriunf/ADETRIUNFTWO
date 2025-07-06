<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CitasController extends Controller
{
    public function index(Request $request)
    {
        return view('gestor.citas.index', [
            'tab' => $request->get('tab', 'programadas'),
            'proyectos' => [],
            'citasCalendar' => [],
        ]);
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'proyecto_id' => 'required',
            'fecha' => 'required|date',
            'motivo' => 'required|string',
        ]);

        return redirect()->route('gestor.citas.index')->with('success', 'Cita guardada.');
    }
}

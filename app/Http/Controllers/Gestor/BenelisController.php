<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectId;
use Illuminate\Http\Request;


class BenelisController extends Controller
{
    public function index()
    {
        $beneficiariosRaw = DB::connection('mongodb')->collection('beneficiarios')->get();

        $beneficiarios = $beneficiariosRaw->map(function ($b) {
            $persona = isset($b['persona_id']) 
                ? DB::connection('mongodb')->collection('personas')->find(new ObjectId($b['persona_id'])) 
                : null;

            return [
                '_id' => (string) $b['_id'],
                'nombre' => $persona['nombres'] ?? 'N/A',
                'dni' => $persona['dni'] ?? 'N/A',
                'telefono' => $persona['telefono'] ?? 'N/A',
                'correo' => $persona['correo'] ?? 'N/A',
            ];
        });

        return view('gestor.beneficiarios.index', compact('beneficiarios'));
    }

    public function encuesta($id)
{
    $beneficiario = DB::connection('mongodb')->collection('beneficiarios')->find(new ObjectId($id));
    return view('gestor.beneficiarios.encuesta', compact('beneficiario'));
}

public function guardarEncuesta(Request $request, $id)
{
    $preguntas = $request->input('preguntas', []);
    $fecha = now()->toDateString();

    try {
        $respuestas = array_map(function ($item) use ($fecha) {
            return [
                'fecha' => $fecha,
                'pregunta' => $item['pregunta'] ?? '',
                'respuesta' => $item['respuesta'] ?? '',
            ];
        }, $preguntas);

        DB::connection('mongodb')->collection('beneficiarios')
            ->where('_id', new ObjectId($id))
            ->push('control', $respuestas);

        return redirect()->route('beneficiarios.index')->with('success', 'Encuesta guardada correctamente.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error al guardar la encuesta: ' . $e->getMessage());
    }
}

    
}

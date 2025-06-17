<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Colonia;

class BeneficiarioController extends Controller
{
    public function index()
    {
        $beneficiariosRaw = DB::connection('mongodb')->collection('beneficiarios')->get();
        $departamentos = Departamento::all();

        // Técnicos con nombre desde personas
        $empleadosRaw = DB::connection('mongodb')
            ->collection('empleados')
            ->where('rol_id', 2)
            ->get();

        $tecnicos = $empleadosRaw->map(function ($emp) {
            $persona = DB::connection('mongodb')->collection('personas')->find($emp['persona_id']);
            return [
                '_id' => (string) $emp['_id'],
                'nombre' => $persona['nombres'] ?? 'Sin nombre'
            ];
        });

        $beneficiarios = $beneficiariosRaw->map(function ($b) {
            $persona = DB::connection('mongodb')->collection('personas')->find($b['persona_id']);

            $departamento = isset($b['departamento_id'])
                ? DB::connection('mongodb')->collection('departamentos')->find($b['departamento_id'])
                : null;

            $municipio = isset($b['municipio_id'])
                ? DB::connection('mongodb')->collection('municipios')->find($b['municipio_id'])
                : null;

            $colonia = isset($b['colonia_id'])
                ? DB::connection('mongodb')->collection('colonias')->find($b['colonia_id'])
                : null;

            return [
                'nomb_per' => $persona['nombres'] ?? '',
                'dni' => $persona['dni'] ?? '',
                'telefono' => $persona['telefono'] ?? '',
                'correo' => $persona['correo'] ?? '',
                'direccion' => $persona['direccion'] ?? '',
                'nombre_departamento' => $departamento['nombre_depto'] ?? null,
                'nombre_municipio' => $municipio['nombre_muni'] ?? null,
                'nombre_colonia' => $colonia['nombre_col'] ?? null,
                'fecha_registro' => $b['fecha_registro'] ?? null,
                'archivo_identidad' => $b['archivo_identidad'] ?? null,
                'registrado_por' => $b['registrado_por'] ?? null,
            ];
        });

        $porMes = $beneficiarios->filter(function ($item) {
            return !is_null($item['fecha_registro']);
        })->groupBy(function ($item) {
            return Carbon::parse($item['fecha_registro'])->format('F');
        });

        $meses = [];
        $valores = [];

        foreach ($porMes as $mes => $items) {
            $meses[] = $mes;
            $valores[] = count($items);
        }

        return view('beneficiarios.formulario', compact(
            'beneficiarios', 'departamentos', 'tecnicos', 'meses', 'valores'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomb_per' => 'required|string|max:255',
            'dni' => 'required|regex:/^[0-9]{4}-[0-9]{4}-[0-9]{5}$/',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:Masculino,Femenino',
            'telefono' => 'required|regex:/^[0-9]{4}-[0-9]{4}$/',
            'correo' => 'required|email',
            'direccion' => 'required|string',
            'departamento_id' => 'required',
            'municipio_id' => 'required',
            'colonia_id' => 'required',
            'tecnico_id' => 'required',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $persona_id = DB::connection('mongodb')->collection('personas')->insertGetId([
            'nombres' => ucwords($request->nomb_per),
            'dni' => $request->dni,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'sexo' => $request->sexo,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'direccion' => $request->direccion,
            'registrado_por' => Auth::id(),
        ]);

        $archivoPath = null;

        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $nombre = time() . '_' . $archivo->getClientOriginalName();
            $archivoPath = $archivo->storeAs('identidades', $nombre, 'public');
        }

        DB::connection('mongodb')->collection('beneficiarios')->insert([
            'persona_id' => $persona_id,
            'departamento_id' => $request->departamento_id,
            'municipio_id' => $request->municipio_id,
            'colonia_id' => $request->colonia_id,
            'tecnico_id' => $request->tecnico_id,
            'fecha_registro' => now(),
            'registrado_por' => Auth::id(),
            'archivo_identidad' => $archivoPath,
        ]);

        return redirect()->back()->with('success', 'Beneficiario registrado correctamente.');
    }
public function obtenerMunicipios($departamento_id)
{
    $municipios = DB::connection('mongodb')
        ->collection('municipios')
        ->where('departamento_id', (int)$departamento_id)
        ->get();

    return response()->json($municipios);
}

public function obtenerColonias($municipio_id)
{
    $colonias = DB::connection('mongodb')
        ->collection('colonias')
        ->where('municipio_id', (int)$municipio_id)
        ->get();

    return response()->json($colonias);
}
}
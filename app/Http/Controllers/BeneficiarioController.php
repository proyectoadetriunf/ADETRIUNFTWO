<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Colonia;

class BeneficiarioController extends Controller
{
    // Mostrar listado + formulario
    public function index()
    {
        $beneficiariosRaw = DB::connection('mongodb')->collection('beneficiarios')->get();
        $departamentos = Departamento::all();

        $tecnicos = DB::connection('mongodb')
            ->collection('empleados')
            ->where('rol_id', 2)
            ->get();

        $beneficiarios = $beneficiariosRaw->map(function ($b) {
            $persona = DB::connection('mongodb')->collection('personas')->find($b['persona_id']);

            return [
                'nomb_per' => $persona['nombres'] ?? '',
                'dni' => $persona['dni'] ?? '',
                'telefono' => $b['telefono'] ?? '',
                'correo' => $b['correo'] ?? '',
                'direccion' => $b['direccion'] ?? '',
                'nombre_departamento' => $b['nombre_departamento'] ?? '',
                'nombre_municipio' => $b['nombre_municipio'] ?? '',
                'nombre_colonia' => $b['nombre_colonia'] ?? '',
            ];
        });

        return view('beneficiarios.formulario', compact('beneficiarios', 'departamentos', 'tecnicos'));
    }

    // Redireccionar create() a index()
    public function create()
    {
        return redirect()->route('beneficiarios.index');
    }

    // Guardar datos en MongoDB (personas + beneficiarios)
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
        ]);

        // Obtener nombres desde MySQL
        $departamento = Departamento::find($request->departamento_id);
        $municipio = Municipio::find($request->municipio_id);
        $colonia = Colonia::find($request->colonia_id);

        // Insertar en PERSONAS
        $persona_id = DB::connection('mongodb')->collection('personas')->insertGetId([
            'persona_id' => 'PER' . rand(100, 999),
            'nombres' => ucwords($request->nomb_per),
            'dni' => $request->dni,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'sexo' => $request->sexo,
        ]);

        // Insertar en BENEFICIARIOS
        DB::connection('mongodb')->collection('beneficiarios')->insert([
            'beneficiario_id' => uniqid('BEN'),
            'persona_id' => $persona_id,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'direccion' => $request->direccion,
            'departamento_id' => $request->departamento_id,
            'municipio_id' => $request->municipio_id,
            'colonia_id' => $request->colonia_id,
            'nombre_departamento' => $departamento->nombre_depto ?? '',
            'nombre_municipio' => $municipio->nombre_muni ?? '',
            'nombre_colonia' => $colonia->nombre_col ?? '',
            'tecnico_id' => $request->tecnico_id,
            'fecha_registro' => now(),
            'estado' => 'activo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('beneficiarios.index')->with('success', 'Beneficiario registrado correctamente.');
    }

    // AJAX: Municipios por departamento
    public function municipiosPorDepartamento($departamentoId)
{
    $municipios = DB::connection('mongodb')
        ->collection('municipios')
        ->where('departamento_id', (int) $departamentoId)
        ->get(['municipio_id', 'nombre_muni']);

    return response()->json($municipios);
}

public function coloniasPorMunicipio($municipioId)
{
    $colonias = DB::connection('mongodb')
        ->collection('colonias')
        ->where('municipio_id', (int) $municipioId)
        ->get(['colonia_id', 'nombre_col']);

    return response()->json($colonias);
}

  
}

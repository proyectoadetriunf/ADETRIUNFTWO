<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Colonia;
use MongoDB\BSON\ObjectId;

class BeneficiarioController extends Controller
{
    public function index()
    {
        $beneficiariosRaw = DB::connection('mongodb')->collection('beneficiarios')->get();
        $departamentos = Departamento::all();

        // Obtener todos los proyectos
        $proyectos = DB::connection('mongodb')->collection('proyectos')->pluck('nombre', '_id');

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
                '_id' => (string) $b['_id'],
                'nomb_per' => $persona['nombres'] ?? '',
                'dni' => $persona['dni'] ?? '',
                'telefono' => $persona['telefono'] ?? '',
                'correo' => $persona['correo'] ?? '',
                'direccion' => $persona['direccion'] ?? '',
                'nombre_departamento' => $departamento['nombre_depto'] ?? null,
                'nombre_municipio' => $municipio['nombre_muni'] ?? null,
                'nombre_colonia' => $colonia['nombre_col'] ?? null,
                'nombre_proyecto' => $b['nombre_proyecto'] ?? null,
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
            'beneficiarios', 'departamentos', 'proyectos', 'meses', 'valores'
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
            'proyecto_id' => 'required|string',
            'archivo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Buscar nombre del proyecto para guardarlo junto
        $proyecto = DB::connection('mongodb')
            ->collection('proyectos')
            ->where('_id', new ObjectId($request->proyecto_id))
            ->first();

        $nombreProyecto = $proyecto['nombre'] ?? 'Sin nombre';

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
            'departamento_id' => (int) $request->departamento_id,
            'municipio_id' => (int) $request->municipio_id,
            'colonia_id' => (int) $request->colonia_id,
            'proyecto_id' => $request->proyecto_id,
            'nombre_proyecto' => $nombreProyecto,
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

    public function destroy($id)
    {
        $deleted = DB::connection('mongodb')
            ->collection('beneficiarios')
            ->where('_id', new ObjectId($id))
            ->delete();

        if ($deleted) {
            return redirect()->route('beneficiarios.index')->with('success', 'Beneficiario eliminado.');
        }

        return redirect()->route('beneficiarios.index')->with('error', 'No se encontró el beneficiario.');
    }

    public function edit($id)
    {
        $beneficiario = DB::connection('mongodb')->collection('beneficiarios')->find($id);

        if (!$beneficiario) {
            return redirect()->route('beneficiarios.index')->with('error', 'Beneficiario no encontrado.');
        }

        $persona = DB::connection('mongodb')->collection('personas')->find($beneficiario['persona_id']);
        $departamentos = DB::connection('mongodb')->collection('departamentos')->get();
        $municipios = DB::connection('mongodb')->collection('municipios')->get();
        $colonias = DB::connection('mongodb')->collection('colonias')->get();

        return view('beneficiarios.editar', compact('beneficiario', 'persona', 'departamentos', 'municipios', 'colonias'));
    }

    public function update(Request $request, $id)
    {
        $beneficiario = DB::connection('mongodb')->collection('beneficiarios')->find($id);

        if (!$beneficiario) {
            return redirect()->route('beneficiarios.index')->with('error', 'Beneficiario no encontrado.');
        }

        $persona_id = $beneficiario['persona_id'];

        DB::connection('mongodb')->collection('personas')->where('_id', $persona_id)->update([
            'nombres' => ucwords($request->nomb_per),
            'dni' => $request->dni,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'sexo' => $request->sexo,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'direccion' => $request->direccion,
        ]);

        DB::connection('mongodb')->collection('beneficiarios')->where('_id', $id)->update([
            'departamento_id' => (int) $request->departamento_id,
            'municipio_id' => (int) $request->municipio_id,
            'colonia_id' => (int) $request->colonia_id,
            // Se puede actualizar también el proyecto si deseas aquí
        ]);

        return redirect()->route('beneficiarios.index')->with('success', 'Beneficiario actualizado correctamente.');
    }
}

<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BeneficiarioController extends Controller
{
    // Mostrar tabla de proyectos asignados al técnico autenticado
    public function index()
    {
        $usuario = Auth::user();

        if (!$usuario || !isset($usuario->_id)) {
            abort(403, 'Usuario no autenticado o inválido');
        }

        $proyectosAsignados = DB::connection('mongodb')
            ->collection('proyectos')
            ->where('tecnico_asignado', (string) $usuario->_id)
            ->get();

        return view('gestor.beneficiarios.index', compact('proyectosAsignados'));
    }

    // Mostrar formulario para registrar beneficiario en un proyecto
    public function formulario($proyecto_id)
    {
        $departamentos = DB::connection('mongodb')->collection('departamentos')->get();

        $tecnicos = DB::connection('mongodb')
            ->collection('empleados')
            ->where('rol_id', 2)
            ->get();

        return view('gestor.beneficiarios.formulario', [
            'departamentos' => $departamentos,
            'tecnicos' => $tecnicos,
            'proyecto_id' => $proyecto_id,
        ]);
    }

    // Guardar beneficiario en MongoDB
    public function guardar(Request $request)
    {
        $request->validate([
            'nomb_per'         => 'required|string|max:255',
            'dni'              => 'required|string|max:50',
            'fecha_nacimiento' => 'required|date',
            'sexo'             => 'required|string',
            'telefono'         => 'required|string',
            'correo'           => 'required|email',
            'direccion'        => 'required|string',
            'departamento_id'  => 'required|string',
            'municipio_id'     => 'required|string',
            'colonia_id'       => 'required|string',
            'tecnico_id'       => 'required|string',
            'proyecto_id'      => 'required|string',
        ]);

        $datos = [
            'nomb_per'         => $request->nomb_per,
            'dni'              => $request->dni,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'sexo'             => $request->sexo,
            'telefono'         => $request->telefono,
            'correo'           => $request->correo,
            'direccion'        => $request->direccion,
            'departamento_id'  => $request->departamento_id,
            'municipio_id'     => $request->municipio_id,
            'colonia_id'       => $request->colonia_id,
            'tecnico_id'       => $request->tecnico_id,
            'proyecto_id'      => $request->proyecto_id,
            'registrado_por'   => (string) Auth::user()->_id,
            'created_at'       => now(),
        ];

        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('beneficiarios', $filename, 'public');
            $datos['archivo'] = $path;
        }

        DB::connection('mongodb')->collection('beneficiarios')->insert($datos);

        return redirect()->route('beneficiarios.index')->with('success', 'Beneficiario registrado correctamente.');
    }

    // API: Obtener municipios según departamento
    public function obtenerMunicipios($departamentoId)
    {
        $municipios = DB::connection('mongodb')
            ->collection('municipios')
            ->where('departamento_id', $departamentoId)
            ->get();

        return $municipios->isEmpty()
            ? response()->json(['error' => 'No se encontraron municipios.'], 404)
            : response()->json($municipios);
    }

    // API: Obtener colonias según municipio
    public function obtenerColonias($municipioId)
    {
        $colonias = DB::connection('mongodb')
            ->collection('colonias')
            ->where('municipio_id', $municipioId)
            ->get();

        return $colonias->isEmpty()
            ? response()->json(['error' => 'No se encontraron colonias.'], 404)
            : response()->json($colonias);
    }
}

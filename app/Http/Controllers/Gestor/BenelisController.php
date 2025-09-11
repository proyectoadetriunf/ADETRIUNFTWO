<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use MongoDB\BSON\ObjectId;
use Carbon\Carbon;

class BenelisController extends Controller
{
    public function index()
    {
        return redirect()->route('beneficiarios.lista');
    }

    public function seleccionarProyecto()
    {
        $usuario = Auth::user();

        if (!$usuario || !isset($usuario->_id)) {
            abort(403, 'Usuario no autenticado o inválido');
        }

        $usuarioId = (string) $usuario->_id;

        $proyectosAsignados = DB::connection('mongodb')
            ->collection('proyectos')
            ->where('tecnico_asignado', $usuarioId)
            ->get();

        return view('gestor.beneficiarios.seleccionar_proyecto', compact('proyectosAsignados'));
    }

    public function formulario($proyecto_id)
    {
        $proyecto = DB::connection('mongodb')->collection('proyectos')->find($proyecto_id);
        $departamentos = DB::connection('mongodb')->collection('departamentos')->get();
        $municipios = DB::connection('mongodb')->collection('municipios')->get();
        $colonias = DB::connection('mongodb')->collection('colonias')->get();

        return view('gestor.beneficiarios.formulario', compact(
            'proyecto', 'departamentos', 'municipios', 'colonias'
        ));
    }

    public function guardar(Request $request)
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
            'nombre_proyecto' => 'required|string',
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
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivoPath = $archivo->storeAs('identidades', $nombreArchivo, 'public');
        }

        DB::connection('mongodb')->collection('beneficiarios')->insert([
            'persona_id' => $persona_id,
            'proyecto_id' => $request->proyecto_id,
            'nombre_proyecto' => $request->nombre_proyecto,
            'departamento_id' => $request->departamento_id,
            'municipio_id' => $request->municipio_id,
            'colonia_id' => $request->colonia_id,
            'fecha_registro' => now(),
            'archivo_identidad' => $archivoPath,
        ]);

        return redirect()->back()->with('success', 'Beneficiario registrado correctamente.');
    }

    public function lista()
    {
        $beneficiariosRaw = DB::connection('mongodb')->collection('beneficiarios')->get();
        $proyectos = DB::connection('mongodb')->collection('proyectos')->get();

        $beneficiarios = $beneficiariosRaw->map(function ($b) {
            $persona = DB::connection('mongodb')->collection('personas')->find($b['persona_id']);
            return [
                '_id' => (string) $b['_id'],
                'nombre' => $persona['nombres'] ?? '',
                'dni' => $persona['dni'] ?? '',
                'telefono' => $persona['telefono'] ?? '',
                'correo' => $persona['correo'] ?? '',
                'proyecto_id' => $b['proyecto_id'] ?? null,
                'nombre_proyecto' => $b['nombre_proyecto'] ?? 'Sin proyecto',
            ];
        });

        return view('gestor.beneficiarios.lista', compact('beneficiarios', 'proyectos'));
    }

    public function encuesta($id)
    {
        $beneficiario = DB::connection('mongodb')->collection('beneficiarios')->find($id);

        if (!$beneficiario) {
            return redirect()->back()->with('error', 'Beneficiario no encontrado.');
        }

        $persona = DB::connection('mongodb')->collection('personas')->find($beneficiario['persona_id']);

        return view('gestor.beneficiarios.encuesta', compact('beneficiario', 'persona'));
    }

    public function guardarEncuesta(Request $request, $id)
    {
        $request->validate([
            'pregunta' => 'required|array',
            'respuesta' => 'required|array',
        ]);

        $beneficiario = DB::connection('mongodb')->collection('beneficiarios')->find($id);

        if (!$beneficiario) {
            return redirect()->back()->with('error', 'Beneficiario no encontrado.');
        }

        $respuestas = [];

        foreach ($request->pregunta as $index => $pregunta) {
            $respuestas[] = [
                'pregunta' => $pregunta,
                'respuesta' => $request->respuesta[$index] ?? '',
                'fecha' => now(),
            ];
        }

        DB::connection('mongodb')
            ->collection('beneficiarios')
            ->where('_id', new ObjectId($id))
            ->update([
                'control' => $respuestas,
            ]);

        return redirect()->route('beneficiarios.lista')->with('success', 'Encuesta guardada correctamente.');
    }

    public function evidenciaYDocumentacion()
    {
        $proyectos = DB::connection('mongodb')->collection('proyectos')->get();
        $beneficiariosRaw = DB::connection('mongodb')->collection('beneficiarios')->get();

        $beneficiarios = $beneficiariosRaw->map(function ($b) {
            $persona = DB::connection('mongodb')->collection('personas')->find($b['persona_id']);

            return [
                'beneficiario_id' => (string) $b['_id'],
                'nombre' => $persona['nombres'] ?? '',
                'proyecto' => $b['nombre_proyecto'] ?? 'Proyecto desconocido',
                'control' => $b['control'] ?? [],
            ];
        });

        return view('gestor.documentacion.index', compact('beneficiarios', 'proyectos'));
    }
}

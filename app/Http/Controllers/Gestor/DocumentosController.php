<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class DocumentosController extends Controller
{
    public function index()
    {
        $usuarioId = (string) auth()->user()->_id;

        // Obtener proyectos asignados al técnico
        $proyectos = DB::connection('mongodb')
            ->collection('proyectos')
            ->where('tecnico_asignado', $usuarioId)
            ->get();

        // Obtener beneficiarios por proyecto
        $beneficiariosPorProyecto = [];

        foreach ($proyectos as $proyecto) {
            $beneficiarios = DB::connection('mongodb')
                ->collection('beneficiarios')
                ->where('proyecto_id', (string) $proyecto['_id'])
                ->get();

            $beneficiariosPorProyecto[(string)$proyecto['_id']] = $beneficiarios->map(function ($b) {
                $nombre = 'Desconocido';

                if (isset($b['persona_id'])) {
                    $persona = DB::connection('mongodb')->collection('personas')->find($b['persona_id']);
                    $nombre = $persona['nombres'] ?? $nombre;
                }

                return [
                    '_id' => (string) $b['_id'],
                    'nombre' => $nombre
                ];
            });
        }

        return view('gestor.documentos.index', [
            'proyectos' => $proyectos,
            'beneficiarios' => $beneficiariosPorProyecto
        ]);
    }

    public function exportar($tipo)
    {
        // 🔹 Obtener encuestas
        $encuestas = [];
        $beneficiarios = DB::connection('mongodb')->collection('beneficiarios')->get();

        foreach ($beneficiarios as $b) {
            $nombre = 'Desconocido';

            if (isset($b['persona_id'])) {
                $persona = DB::connection('mongodb')->collection('personas')->find($b['persona_id']);
                $nombre = $persona['nombres'] ?? $nombre;
            }

            if (isset($b['control']) && is_array($b['control'])) {
                foreach ($b['control'] as $c) {
                    $encuestas[] = [
                        'beneficiario' => $nombre,
                        'fecha' => $c['fecha'] ?? '',
                        'pregunta' => $c['pregunta'] ?? '',
                        'respuesta' => $c['respuesta'] ?? '',
                    ];
                }
            }
        }

        // 🔹 Renderizar contenido HTML desde vista
        $html = View::make('gestor.documentos.exportable', compact('encuestas'))->render();
        $filename = "documentacion_" . now()->format('Ymd_His');

        switch ($tipo) {
            case 'excel':
                $headers = [
                    "Content-Type" => "application/vnd.ms-excel",
                    "Content-Disposition" => "attachment; filename=$filename.xls"
                ];
                break;

            case 'word':
                $headers = [
                    "Content-Type" => "application/msword",
                    "Content-Disposition" => "attachment; filename=$filename.doc"
                ];
                break;

            case 'pdf':
            default:
                $headers = [
                    "Content-Type" => "application/pdf",
                    "Content-Disposition" => "attachment; filename=$filename.pdf"
                ];
                break;
        }

        return response($html, 200, $headers);
    }
}

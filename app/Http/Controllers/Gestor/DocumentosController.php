<?php

namespace App\Http\Controllers\Gestor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class DocumentosController extends Controller
{
    public function index()
    {
        // 🔹 Obtener imágenes de carpetas 'evidencia' y 'citas'
        $imagenes = [];
        foreach (['evidencia', 'citas'] as $carpeta) {
            $archivos = Storage::files("public/$carpeta");
            foreach ($archivos as $ruta) {
                if (preg_match('/\.(jpg|jpeg|png|gif|bmp|webp)$/i', $ruta)) {
                    $imagenes[] = [
                        'ruta' => str_replace('public/', '', $ruta)
                    ];
                }
            }
        }

        // 🔹 Obtener encuestas de los beneficiarios
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

        // ✅ Retornar vista con ambas variables
        return view('gestor.documentos.index', compact('imagenes', 'encuestas'));
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

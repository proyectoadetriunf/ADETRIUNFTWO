<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

class SolicitudController extends Controller
{
    public function create()
    {
        $administradores = \DB::connection('mongodb')
            ->collection('users')
            ->where('rol_id', 'admin')
            ->get();

        return view('solicitudes.crear', compact('administradores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'anio' => 'required|integer|min:2024',
            'costo' => 'required|numeric|min:0',
            'admin_id' => 'required|string',
        ]);

        $datos = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'anio' => intval($request->anio),
            'costo' => floatval($request->costo),
            'estado' => 'Pendiente',
            'admin_id' => new ObjectId($request->admin_id),
            'created_at' => new UTCDateTime(now()),
        ];

        \DB::connection('mongodb')->collection('solicitudes')->insert($datos);

        return redirect()->route('solicitudes.create')->with('success', 'Solicitud enviada con éxito.');
    }

    public function mostrar(Request $request)
    {
        $tab = $request->query('tab', 'pendientes');

        $solicitudes = \DB::connection('mongodb')->collection('solicitudes')->get();

        $pendientes = $solicitudes->filter(fn($s) => ($s['estado'] ?? '') === 'Pendiente');
        $aceptadas  = $solicitudes->filter(fn($s) => ($s['estado'] ?? '') === 'Aceptada');
        $rechazadas = $solicitudes->filter(fn($s) => ($s['estado'] ?? '') === 'Rechazada');

        return view('solicitudes.mostrar', compact('pendientes', 'aceptadas', 'rechazadas', 'tab'));
    }

    public function aceptar(Request $request, $id)
    {
        $request->validate([
            'costo_aprobado' => 'required|numeric|min:0',
        ]);

        $solicitud = \DB::connection('mongodb')->collection('solicitudes')->find($id);

        \DB::connection('mongodb')->collection('solicitudes')->where('_id', new ObjectId($id))->update([
            'estado' => 'Aceptada',
            'costo_aprobado' => floatval($request->costo_aprobado),
            'updated_at' => new UTCDateTime(now()),
        ]);

        \DB::connection('mongodb')->collection('proyectos')->insert([
            'nombre' => $solicitud['nombre'] ?? '',
            'descripcion' => $solicitud['descripcion'] ?? '',
            'anio' => $solicitud['anio'] ?? '',
            'costo' => floatval($request->costo_aprobado),
            'estado' => 'En progreso',
            'created_at' => new UTCDateTime(now()),
        ]);

        return redirect()->route('solicitudes.mostrar', ['tab' => 'aceptadas'])
                         ->with('success', '✅ Solicitud aceptada y proyecto creado.');
    }

    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'comentario' => 'required|string',
        ]);

        \DB::connection('mongodb')->collection('solicitudes')->where('_id', new ObjectId($id))->update([
            'estado' => 'Rechazada',
            'comentario' => $request->comentario,
            'updated_at' => new UTCDateTime(now()),
        ]);

        return redirect()->route('solicitudes.mostrar', ['tab' => 'rechazadas'])
                         ->with('success', '⚠️ Solicitud rechazada con comentario.');
    }

    public function exportar(Request $request)
    {
        $estado = $request->estado ?? 'Pendiente';

        $solicitudes = \DB::connection('mongodb')
            ->collection('solicitudes')
            ->where('estado', $estado)
            ->get();

        $filename = 'solicitudes_' . strtolower($estado) . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($solicitudes) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nombre', 'Año', 'Costo Solicitado', 'Costo Aprobado', 'Estado', 'Comentario']);

            foreach ($solicitudes as $s) {
                fputcsv($handle, [
                    $s['nombre'] ?? '',
                    $s['anio'] ?? '',
                    $s['costo'] ?? '',
                    $s['costo_aprobado'] ?? '',
                    $s['estado'] ?? '',
                    $s['comentario'] ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportarHtml($estado)
    {
        $solicitudes = \DB::connection('mongodb')
            ->collection('solicitudes')
            ->where('estado', $estado)
            ->get();

        return view('solicitudes.reporte_html', [
            'solicitudes' => $solicitudes,
            'estado' => ucfirst($estado),
        ]);
    }

    public function exportarWord($estado)
    {
        $html = $this->exportarHtml($estado)->render();
        $filename = "solicitudes_{$estado}.doc";

        return response($html)
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', "attachment; filename=$filename");
    }

    public function exportarPdf($estado)
    {
        $html = $this->exportarHtml($estado)->render();
        $filename = "solicitudes_{$estado}.pdf";

        return response($html)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=$filename");
    }

    public function exportarExcel($estado)
    {
        $html = $this->exportarHtml($estado)->render();
        $filename = "solicitudes_{$estado}.xls";

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=$filename");
    }
}

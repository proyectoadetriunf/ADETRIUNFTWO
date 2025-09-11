<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // Obtener información del administrador y usuario solicitante
        $administrador = DB::connection('mongodb')->collection('users')
            ->where('_id', new ObjectId($request->admin_id))
            ->first();

        $usuario = auth()->user();
        $fechaHoraCreacion = now()->setTimezone('America/Tegucigalpa')->format('d/m/Y h:i A');

        $datos = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'anio' => intval($request->anio),
            'costo' => floatval($request->costo),
            'estado' => 'Pendiente',
            'admin_id' => new ObjectId($request->admin_id),
            'solicitante_id' => new ObjectId($usuario->_id),
            'solicitante_nombre' => $usuario->name,
            'created_at' => new UTCDateTime(now()),
            'fecha_hora_creacion' => $fechaHoraCreacion
        ];

        DB::connection('mongodb')->collection('solicitudes')->insert($datos);

        // Enviar notificación al administrador
        if ($administrador) {
            $mensaje = "Nueva solicitud de proyecto recibida: '{$request->nombre}' por {$usuario->name}. Año: {$request->anio}. Costo solicitado: Lps. " . number_format($request->costo, 2) . ". Enviada el {$fechaHoraCreacion}.";
            
            try {
                DB::connection('mongodb')->collection('notificaciones_personalizadas')->insert([
                    'user_id'    => new ObjectId($request->admin_id),
                    'mensaje'    => $mensaje,
                    'leida'      => false,
                    'created_at' => now(),
                    'fecha_hora_notificacion' => $fechaHoraCreacion,
                    'tipo' => 'solicitud_proyecto'
                ]);
                
                Log::info('Notificación de solicitud creada para admin ID: ' . $request->admin_id . ' el ' . $fechaHoraCreacion);
                
            } catch (\Exception $e) {
                Log::error('Error al crear notificación de solicitud: ' . $e->getMessage());
            }
        }

        return redirect()->route('solicitudes.create')->with('success', 'Solicitud enviada con éxito y notificación enviada al administrador.');
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

        $solicitud = DB::connection('mongodb')->collection('solicitudes')->find($id);
        $fechaHoraActualizacion = now()->setTimezone('America/Tegucigalpa')->format('d/m/Y h:i A');

        DB::connection('mongodb')->collection('solicitudes')->where('_id', new ObjectId($id))->update([
            'estado' => 'Aceptada',
            'costo_aprobado' => floatval($request->costo_aprobado),
            'updated_at' => new UTCDateTime(now()),
            'fecha_hora_aprobacion' => $fechaHoraActualizacion
        ]);

        DB::connection('mongodb')->collection('proyectos')->insert([
            'nombre' => $solicitud['nombre'] ?? '',
            'descripcion' => $solicitud['descripcion'] ?? '',
            'anio' => $solicitud['anio'] ?? '',
            'costo' => floatval($request->costo_aprobado),
            'estado' => 'En progreso',
            'created_at' => new UTCDateTime(now()),
            'fecha_hora_creacion' => $fechaHoraActualizacion
        ]);

        // Enviar notificación al solicitante sobre la aceptación
        if (isset($solicitud['solicitante_id'])) {
            $mensaje = "¡Buenas noticias! Tu solicitud de proyecto '{$solicitud['nombre']}' ha sido ACEPTADA. Costo aprobado: Lps. " . number_format($request->costo_aprobado, 2) . ". Aprobada el {$fechaHoraActualizacion}.";
            
            try {
                DB::connection('mongodb')->collection('notificaciones_personalizadas')->insert([
                    'user_id'    => new ObjectId($solicitud['solicitante_id']),
                    'mensaje'    => $mensaje,
                    'leida'      => false,
                    'created_at' => now(),
                    'fecha_hora_notificacion' => $fechaHoraActualizacion,
                    'tipo' => 'solicitud_aceptada'
                ]);
                
                Log::info('Notificación de aceptación enviada al solicitante ID: ' . $solicitud['solicitante_id']);
                
            } catch (\Exception $e) {
                Log::error('Error al crear notificación de aceptación: ' . $e->getMessage());
            }
        }

        return redirect()->route('solicitudes.mostrar', ['tab' => 'aceptadas'])
                         ->with('success', '✅ Solicitud aceptada, proyecto creado y notificación enviada al solicitante.');
    }

    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'comentario' => 'required|string',
        ]);

        $solicitud = DB::connection('mongodb')->collection('solicitudes')->find($id);
        $fechaHoraRechazo = now()->setTimezone('America/Tegucigalpa')->format('d/m/Y h:i A');

        DB::connection('mongodb')->collection('solicitudes')->where('_id', new ObjectId($id))->update([
            'estado' => 'Rechazada',
            'comentario' => $request->comentario,
            'updated_at' => new UTCDateTime(now()),
            'fecha_hora_rechazo' => $fechaHoraRechazo
        ]);

        // Enviar notificación al solicitante sobre el rechazo
        if (isset($solicitud['solicitante_id'])) {
            $mensaje = "Tu solicitud de proyecto '{$solicitud['nombre']}' ha sido RECHAZADA. Motivo: {$request->comentario}. Rechazada el {$fechaHoraRechazo}.";
            
            try {
                DB::connection('mongodb')->collection('notificaciones_personalizadas')->insert([
                    'user_id'    => new ObjectId($solicitud['solicitante_id']),
                    'mensaje'    => $mensaje,
                    'leida'      => false,
                    'created_at' => now(),
                    'fecha_hora_notificacion' => $fechaHoraRechazo,
                    'tipo' => 'solicitud_rechazada'
                ]);
                
                Log::info('Notificación de rechazo enviada al solicitante ID: ' . $solicitud['solicitante_id']);
                
            } catch (\Exception $e) {
                Log::error('Error al crear notificación de rechazo: ' . $e->getMessage());
            }
        }

        return redirect()->route('solicitudes.mostrar', ['tab' => 'rechazadas'])
                         ->with('success', '⚠️ Solicitud rechazada con comentario y notificación enviada al solicitante.');
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

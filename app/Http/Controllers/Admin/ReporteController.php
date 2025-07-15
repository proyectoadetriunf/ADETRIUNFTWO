<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Finanza;
use App\Models\Colonia;

class ReporteController extends Controller
{
    // 📈 Avances por proyecto
    public function avances()
    {
        $proyectos = Proyecto::all();

        foreach ($proyectos as $proyecto) {
            $evidencias = $proyecto->evidencias ?? [];

            $totalGasto = collect($evidencias)
                ->pluck('monto_gasto')
                ->map(fn($m) => floatval($m ?? 0))
                ->sum();

            $proyecto->monto_ejecutado = $totalGasto;
        }

        return view('admin.reportes.avances', compact('proyectos'));
    }

    // 💰 Reporte Financiero
    public function financieros()
{
    $proyectos = Proyecto::all();

    foreach ($proyectos as $proyecto) {
        $proyecto->costo = floatval($proyecto->costo ?? 0);

        $evidencias = $proyecto->evidencias ?? [];

        $montoEjecutado = collect($evidencias)
            ->pluck('monto_gasto')
            ->map(fn($m) => floatval($m ?? 0))
            ->sum();

        $proyecto->monto_ejecutado = $montoEjecutado;
        $proyecto->saldo = $proyecto->costo - $montoEjecutado;
    }

    return view('admin.reportes.financieros', compact('proyectos'));
}


    // 🏘️ Inversión por Comunidad
    public function comunidades()
    {
        $colonias = Colonia::all();
        $proyectos = Proyecto::all();
        $reporte = [];

        foreach ($colonias as $colonia) {
            $nombreCol = strtolower($colonia->nombre_col);

            // Buscar proyectos que mencionan la colonia
            $proyectosAsociados = $proyectos->filter(function ($proyecto) use ($nombreCol) {
                return str_contains(strtolower($proyecto->nombre), $nombreCol) ||
                       str_contains(strtolower($proyecto->descripcion), $nombreCol);
            });

            if ($proyectosAsociados->isNotEmpty()) {
                $totalCosto = $proyectosAsociados->sum(function ($p) {
                    return floatval($p->costo ?? 0);
                });

                $montoEjecutadoTotal = $proyectosAsociados->sum(function ($p) {
                    return collect($p->evidencias)->sum(fn($e) => floatval($e['monto_gasto'] ?? 0));
                });

                $saldoTotal = $totalCosto - $montoEjecutadoTotal;

                $anios = $proyectosAsociados->pluck('anio')->unique()->sort()->toArray();

                $reporte[] = [
                    'colonia' => $colonia->nombre_col,
                    'municipio_id' => $colonia->municipio_id,
                    'proyectos_count' => $proyectosAsociados->count(),
                    'costo_total' => $totalCosto,
                    'monto_ejecutado_total' => $montoEjecutadoTotal,
                    'saldo_total' => $saldoTotal,
                    'anios' => implode(', ', $anios),
                    'proyectos' => $proyectosAsociados,
                ];
            }
        }

        return view('admin.reportes.comunidades', compact('reporte'));
    }
}






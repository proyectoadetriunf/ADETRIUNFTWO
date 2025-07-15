@extends('adminlte::page')

@section('title', '📊 Reporte Financiero')

@section('content')
<div class="container mt-4 mb-5">
    <h1 class="text-center mb-5 text-primary font-weight-bold">
        Reporte Financiero de Proyectos
    </h1>

    @php
        $totalAsignado = $proyectos->sum(fn($p) => floatval($p->costo ?? 0));
        $totalEjecutado = $proyectos->sum(fn($p) => floatval($p->monto_ejecutado ?? 0));
        $totalSaldo = $totalAsignado - $totalEjecutado;
    @endphp

    {{-- Resumen general --}}
    <div class="row text-center mb-5 justify-content-center">
        <div class="col-md-4 mb-3">
            <div class="card shadow border-left-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total Asignado</h6>
                    <h3 class="text-primary font-weight-bold">L. {{ number_format($totalAsignado, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow border-left-success">
                <div class="card-body">
                    <h6 class="text-muted">Total Ejecutado</h6>
                    <h3 class="text-success font-weight-bold">L. {{ number_format($totalEjecutado, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow border-left-warning">
                <div class="card-body">
                    <h6 class="text-muted">Saldo Disponible</h6>
                    <h3 class="font-weight-bold {{ $totalSaldo < 0 ? 'text-danger' : 'text-secondary' }}">
                        L. {{ number_format($totalSaldo, 2) }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de proyectos --}}
    @if($proyectos->isEmpty())
        <div class="alert alert-info text-center">
            No hay proyectos disponibles actualmente.
        </div>
    @else
        <div class="table-responsive bg-white shadow-sm rounded p-3">
            <table class="table table-bordered table-hover table-striped align-middle text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>📁 Proyecto</th>
                        <th>Año</th>
                        <th>💰 Asignado</th>
                        <th>💸 Ejecutado</th>
                        <th>💵 Saldo</th>
                        <th>📉 % Ejecución</th>
                        <th>📊 Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proyectos as $proyecto)
                        @php
                            $costo = floatval($proyecto->costo ?? 0);
                            $ejecutado = floatval($proyecto->monto_ejecutado ?? 0);
                            $saldo = $costo - $ejecutado;
                            $porcentaje = $costo > 0 ? round(($ejecutado / $costo) * 100, 2) : 0;
                        @endphp
                        <tr>
                            <td class="text-left">
                                <strong>{{ $proyecto->nombre }}</strong><br>
                                <small class="text-muted">{{ $proyecto->descripcion }}</small>
                            </td>
                            <td>{{ $proyecto->anio ?? 'N/D' }}</td>
                            <td class="text-primary font-weight-bold">
                                L. {{ number_format($costo, 2) }}
                            </td>
                            <td class="text-success font-weight-bold">
                                L. {{ number_format($ejecutado, 2) }}
                            </td>
                            <td class="{{ $saldo < 0 ? 'text-danger' : 'text-secondary' }} font-weight-bold">
                                L. {{ number_format($saldo, 2) }}
                            </td>
                            <td style="min-width: 180px;">
                                <div class="progress">
                                    <div class="progress-bar 
                                        {{ $porcentaje >= 100 ? 'bg-success' : ($porcentaje >= 50 ? 'bg-info' : 'bg-warning') }}"
                                        role="progressbar"
                                        style="width: {{ min($porcentaje, 100) }}%;">
                                        {{ $porcentaje }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($costo == 0)
                                    <span class="badge badge-secondary">Sin presupuesto</span>
                                @elseif($saldo < 0)
                                    <span class="badge badge-danger">⚠️ Excedido</span>
                                @elseif($porcentaje >= 100)
                                    <span class="badge badge-success">✅ Finalizado</span>
                                @else
                                    <span class="badge badge-primary">🕒 En ejecución</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection













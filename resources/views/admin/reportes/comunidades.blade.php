@extends('adminlte::page')

@section('title', '📊 Reporte por Comunidades')

@section('content')
<div class="container mt-4 mb-5">
    <h1 class="text-center mb-5 text-primary font-weight-bold">
        Inversión por Comunidades
    </h1>

    @forelse ($reporte as $fila)
        <div class="card shadow mb-4 border-0 rounded-lg">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="font-weight-bold mb-1 text-dark">
                        🏡 Comunidad: {{ ucfirst($fila['colonia']) }}
                    </h5>
                    <p class="mb-0 text-muted">
                        🏛️ Municipio ID: {{ $fila['municipio_id'] }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="badge badge-pill badge-primary px-3 py-2">
                        {{ $fila['proyectos_count'] }} Proyectos
                    </span>
                </div>
            </div>

            <div class="card-body bg-light">
                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <div class="p-3 border rounded bg-white shadow-sm">
                            <div class="text-muted">💰 Inversión Total</div>
                            <h5 class="text-primary font-weight-bold">
                                L {{ number_format($fila['costo_total'], 2) }}
                            </h5>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="p-3 border rounded bg-white shadow-sm">
                            <div class="text-muted">💵 Monto Ejecutado</div>
                            <h5 class="text-success font-weight-bold">
                                L {{ number_format($fila['monto_ejecutado_total'], 2) }}
                            </h5>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="p-3 border rounded bg-white shadow-sm">
                            <div class="text-muted">📉 Saldo Restante</div>
                            <h5 class="font-weight-bold {{ $fila['saldo_total'] < 0 ? 'text-danger' : 'text-secondary' }}">
                                L {{ number_format($fila['saldo_total'], 2) }}
                            </h5>
                        </div>
                    </div>
                </div>

                <hr>

                <h6 class="font-weight-bold text-secondary mb-3">📋 Proyectos:</h6>

                <ul class="list-group">
                    @foreach ($fila['proyectos'] as $proyecto)
                        @php
                            $costo = floatval($proyecto->costo ?? 0);
                            $ejecutado = collect($proyecto->evidencias)->sum(fn($e) => floatval($e['monto_gasto'] ?? 0));
                            $saldo = $costo - $ejecutado;
                        @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $proyecto->nombre }}</strong>
                                <small class="text-muted d-block">{{ $proyecto->descripcion }}</small>
                                <small class="text-muted">📅 Año: {{ $proyecto->anio ?? 'N/D' }}</small>
                            </div>
                            <div class="text-right">
                                <span class="d-block text-primary font-weight-bold">Asignado: L {{ number_format($costo, 2) }}</span>
                                <span class="d-block text-success">Ejecutado: L {{ number_format($ejecutado, 2) }}</span>
                                <span class="d-block {{ $saldo < 0 ? 'text-danger' : 'text-secondary' }}">Saldo: L {{ number_format($saldo, 2) }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            No se encontraron datos de inversión por comunidades.
        </div>
    @endforelse
</div>
@endsection




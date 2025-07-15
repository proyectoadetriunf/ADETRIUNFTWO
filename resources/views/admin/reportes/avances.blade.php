@extends('adminlte::page')

@section('title', '📈 Avances por Proyecto')

@section('content')
<div class="container mt-4 mb-5">
    <h1 class="mb-4 text-center" style="color: #007bff; font-weight: 700;">
        Avances por Proyecto
    </h1>

    @forelse($proyectos as $index => $proyecto)
        @php
            $totalAvance = collect($proyecto->seguimientos)->sum('avance');
            $avanceAcumulado = min($totalAvance, 100);

            $totalEjecutado = collect($proyecto->evidencias)
                ->pluck('monto_gasto')
                ->filter()
                ->sum(fn($monto) => floatval($monto));
        @endphp

        <div class="card mb-4 shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column text-left">
                    <span class="text-muted font-weight-bold" style="font-size: 1.1rem;">
                        <i class="fas fa-calendar-alt mr-1"></i> Año: {{ $proyecto->anio }}
                    </span>
                </div>
                <div class="text-center flex-grow-1 px-4">
                    <h4 class="mb-1 font-weight-bold text-dark">{{ ucfirst($proyecto->nombre) }}</h4>
                    <p class="mb-0 text-muted">{{ $proyecto->descripcion }}</p>
                </div>
                <div class="text-right">
                    <span class="badge badge-pill px-3 py-2 text-white" style="background-color: #17a2b8; font-size: 0.95rem;">
                        {{ ucfirst($proyecto->estado ?? 'Desconocido') }}
                    </span>
                </div>
            </div>

            <div class="card-body bg-light rounded-bottom">
                {{-- Gráfico de avance --}}
                <div class="mb-4">
                    <h6 class="text-secondary font-weight-bold mb-2">📊 Avance acumulado del proyecto</h6>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $avanceAcumulado }}%;" aria-valuenow="{{ $avanceAcumulado }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $avanceAcumulado }}%
                        </div>
                    </div>
                </div>

                {{-- Total ejecutado --}}
                <div class="mb-4">
                    <h6 class="text-secondary font-weight-bold mb-2">💵 Total Ejecutado</h6>
                    <div class="card bg-white shadow-sm border-left border-success pl-3 py-2">
                        <h5 class="mb-0 text-success font-weight-bold">
                            L. {{ number_format($totalEjecutado, 2) }}
                        </h5>
                    </div>
                </div>

                {{-- Seguimientos --}}
                <h6 class="text-secondary font-weight-bold mb-3">📝 Seguimientos del Proyecto</h6>

                @if(!empty($proyecto->seguimientos) && count($proyecto->seguimientos) > 0)
                    <div class="list-group">
                        @foreach ((array) $proyecto->seguimientos as $seguimiento)
                            <div class="list-group-item list-group-item-action mb-2 rounded d-flex justify-content-between align-items-center border-left border-info bg-white">
                                <div>
                                    <div class="mb-1">
                                        <strong>📅 Fecha:</strong> {{ $seguimiento['fecha'] ?? 'Sin fecha' }}
                                    </div>
                                    <div>
                                        <strong>💬 Comentario:</strong> {{ $seguimiento['comentario'] ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-success badge-pill py-2 px-3" style="font-size: 1rem;">
                                        {{ $seguimiento['avance'] ?? 0 }}%
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        Este proyecto aún no tiene seguimientos registrados.
                    </div>
                @endif

                {{-- Evidencias (con botón y modal) --}}
                @if(!empty($proyecto->evidencias))
                    <div class="mt-4">
                        <button class="btn btn-outline-secondary btn-sm mb-2" type="button" data-toggle="collapse" data-target="#evidencias-{{ $index }}">
                            📂 Mostrar evidencias
                        </button>

                        <div class="collapse" id="evidencias-{{ $index }}">
                            <div class="d-flex flex-wrap">
                                @foreach($proyecto->evidencias as $evidIndex => $evidencia)
                                    @if(!empty($evidencia['imagen']))
                                        @php
                                            $imagePath = str_replace('public/', 'storage/', $evidencia['imagen']);
                                        @endphp

                                        <div class="m-2">
                                            <img src="{{ asset($imagePath) }}"
                                                alt="evidencia"
                                                class="img-thumbnail evid-img"
                                                style="max-height: 120px; cursor: pointer;"
                                                data-toggle="modal"
                                                data-target="#modalEvidencia-{{ $index }}-{{ $evidIndex }}">
                                        </div>

                                        {{-- Modal para visualizar imagen --}}
                                        <div class="modal fade" id="modalEvidencia-{{ $index }}-{{ $evidIndex }}" tabindex="-1" role="dialog" aria-labelledby="modalEvidenciaLabel{{ $index }}-{{ $evidIndex }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body text-center p-3">
                                                        <img src="{{ asset($imagePath) }}" alt="evidencia" class="img-fluid rounded">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            No hay proyectos registrados actualmente.
        </div>
    @endforelse
</div>
@endsection


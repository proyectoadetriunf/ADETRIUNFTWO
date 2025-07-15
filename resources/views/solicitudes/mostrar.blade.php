@extends('adminlte::page')

@section('title', 'Solicitudes de Proyectos')

@section('content')
<div class="container">
    <h1 class="mb-4">📥 Solicitudes Recibidas</h1>

    <!-- Navegación con tabs -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'pendientes' ? 'active' : '' }}" href="{{ route('solicitudes.mostrar', ['tab' => 'pendientes']) }}">Pendientes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'aceptadas' ? 'active' : '' }}" href="{{ route('solicitudes.mostrar', ['tab' => 'aceptadas']) }}">Aceptadas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'rechazadas' ? 'active' : '' }}" href="{{ route('solicitudes.mostrar', ['tab' => 'rechazadas']) }}">Rechazadas</a>
        </li>
    </ul>

    <!-- Contenido por pestaña -->
    @if($tab === 'pendientes')
        <h4 class="mb-3">Solicitudes Pendientes</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Año</th>
                    <th>Costo</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendientes as $solicitud)
                    <tr>
                        <td>{{ $solicitud['nombre'] ?? '' }}</td>
                        <td>{{ $solicitud['anio'] ?? '' }}</td>
                        <td>L. {{ number_format($solicitud['costo'] ?? 0, 2) }}</td>
                        <td>{{ $solicitud['descripcion'] ?? '' }}</td>
                        <td>
                            <form action="{{ route('solicitudes.aceptar', $solicitud['_id']) }}" method="POST" class="mb-2">
                                @csrf
                                <input type="number" name="costo_aprobado" class="form-control mb-1" step="0.01" placeholder="Costo aprobado" required>
                                <button type="submit" class="btn btn-success btn-sm">Aceptar</button>
                            </form>
                            <form action="{{ route('solicitudes.rechazar', $solicitud['_id']) }}" method="POST">
                                @csrf
                                <textarea name="comentario" class="form-control mb-1" rows="1" placeholder="Motivo rechazo" required></textarea>
                                <button type="submit" class="btn btn-danger btn-sm">Rechazar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No hay solicitudes pendientes.</td></tr>
                @endforelse
            </tbody>
        </table>

    @elseif($tab === 'aceptadas')
        <h4 class="mb-3">Solicitudes Aceptadas</h4>
        <div class="mb-3">
            <a href="{{ route('solicitudes.exportar.word', 'Aceptada') }}" class="btn btn-info">📝 Word</a>
            <a href="{{ route('solicitudes.exportar.excel', 'Aceptada') }}" class="btn btn-success">📊 Excel</a>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Año</th>
                    <th>Costo Aprobado</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($aceptadas as $solicitud)
                    <tr>
                        <td>{{ $solicitud['nombre'] ?? '' }}</td>
                        <td>{{ $solicitud['anio'] ?? '' }}</td>
                        <td>L. {{ number_format($solicitud['costo_aprobado'] ?? 0, 2) }}</td>
                        <td>{{ $solicitud['descripcion'] ?? '' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No hay solicitudes aceptadas.</td></tr>
                @endforelse
            </tbody>
        </table>

    @elseif($tab === 'rechazadas')
        <h4 class="mb-3">Solicitudes Rechazadas</h4>
        <div class="mb-3">
            <a href="{{ route('solicitudes.exportar.word', 'Rechazada') }}" class="btn btn-info">📝 Word</a>
            <a href="{{ route('solicitudes.exportar.excel', 'Rechazada') }}" class="btn btn-success">📊 Excel</a>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Año</th>
                    <th>Costo</th>
                    <th>Comentario</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rechazadas as $solicitud)
                    <tr>
                        <td>{{ $solicitud['nombre'] ?? '' }}</td>
                        <td>{{ $solicitud['anio'] ?? '' }}</td>
                        <td>L. {{ number_format($solicitud['costo'] ?? 0, 2) }}</td>
                        <td>{{ $solicitud['comentario'] ?? 'Sin comentario' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No hay solicitudes rechazadas.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif
</div>
@endsection

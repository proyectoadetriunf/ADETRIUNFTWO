@extends('adminlte::page')

@section('title', 'Avances del Proyecto')

@section('content')
<div class="container">
    <h1 class="mb-4">📈 Avances del Proyecto: {{ $proyecto['nombre'] }}</h1>

    <!-- Botones de exportación y volver -->
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('gestor.proyectos.exportarExcel', $proyecto['_id']) }}" class="btn btn-success">📥 Exportar Excel</a>
        <a href="{{ route('gestor.proyectos.exportarWord', $proyecto['_id']) }}" class="btn btn-primary">📝 Exportar Word</a>
        <a href="{{ route('gestor.asignados') }}" class="btn btn-secondary">🔙 Volver</a>
    </div>

    <!-- TOTALES -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Costo Total</h5>
                    <h3>L. {{ number_format($proyecto['costo'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Gasto Total</h5>
                    <h3>L. {{ number_format($proyecto['gasto_total'] ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Saldo Restante</h5>
                    <h3>L. {{ number_format(($proyecto['costo'] ?? 0) - ($proyecto['gasto_total'] ?? 0), 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- PROGRESO DEL GASTO -->
    <div class="mb-4">
        <label>Progreso del Gasto</label>
        @php
            $porcentajeGasto = ($proyecto['costo'] > 0) ? min(100, (($proyecto['gasto_total'] ?? 0) / $proyecto['costo']) * 100) : 0;
        @endphp
        <div class="progress">
            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $porcentajeGasto }}%">
                {{ number_format($porcentajeGasto, 2) }}%
            </div>
        </div>
    </div>

    <!-- PROGRESO DEL PROYECTO -->
    <div class="mb-4">
        <label>Progreso del Proyecto</label>
        @php
            $porcentajeProyecto = $proyecto['progreso'] ?? 0;
        @endphp
        <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $porcentajeProyecto }}%">
                {{ number_format($porcentajeProyecto, 2) }}%
            </div>
        </div>
    </div>

    <!-- ACTUALIZAR PROGRESO -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            Actualizar Progreso del Proyecto
        </div>
        <div class="card-body">
            <form action="{{ route('gestor.proyectos.actualizarProgreso', $proyecto['_id']) }}" method="POST">
                @csrf
                <label>📌 Progreso Manual: <span id="valorProgreso">{{ $proyecto['progreso'] ?? 0 }}</span>%</label>
                <input type="range" name="progreso" value="{{ $proyecto['progreso'] ?? 0 }}" min="0" max="100" oninput="document.getElementById('valorProgreso').innerText = this.value" class="form-range">
                <button class="btn btn-primary mt-2" type="submit">Actualizar</button>
            </form>
        </div>
    </div>

    <!-- FORMULARIO REGISTRAR AVANCE -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            Registrar Avance
        </div>
        <div class="card-body">
            <form action="{{ route('gestor.proyectos.avances.guardar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="proyecto_id" value="{{ $proyecto['_id'] }}">

                <div class="form-group">
                    <label for="descripcion">Descripción del Avance</label>
                    <textarea name="descripcion" class="form-control" required></textarea>
                </div>

                <div class="form-group">
                    <label for="monto_gasto">Monto del Gasto (L.)</label>
                    <input type="number" name="monto_gasto" class="form-control" step="0.01">
                </div>

                <div class="form-group">
                    <label for="imagen">Evidencia Fotográfica</label>
                    <input type="file" name="imagen" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Guardar Avance</button>
            </form>
        </div>
    </div>

    <!-- HISTORIAL DE AVANCES -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            Historial de Avances
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Gasto</th>
                        <th>Fecha</th>
                        <th>Imagen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proyecto['evidencias'] ?? [] as $ev)
                        <tr>
                            <td>{{ $ev['descripcion'] }}</td>
                            <td>L. {{ number_format($ev['monto_gasto'] ?? $ev['monto'] ?? 0, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($ev['fecha'])->format('d/m/Y') }}</td>
                            <td>
                                @if(!empty($ev['imagen']))
                                    <a href="{{ asset('storage/' . ltrim($ev['imagen'], 'public/')) }}" target="_blank">Ver Imagen</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay avances registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

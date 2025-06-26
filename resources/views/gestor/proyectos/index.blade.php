@extends('adminlte::page')

@section('title', 'Gestión de Proyectos')

@section('content')
<div class="container">
    <h1>🗂️ Gestión de Proyectos</h1>

    <!-- Navegación -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'ver' ? 'active' : '' }}" href="{{ route('gestor.proyectos.index', ['tab' => 'ver']) }}">📄 Ver Proyectos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'crear' ? 'active' : '' }}" href="{{ route('gestor.proyectos.index', ['tab' => 'crear']) }}">➕ Crear Proyecto</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'seguimiento' ? 'active' : '' }}" href="{{ route('gestor.proyectos.index', ['tab' => 'seguimiento']) }}">📊 Seguimiento</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'evidencias' ? 'active' : '' }}" href="{{ route('gestor.proyectos.index', ['tab' => 'evidencias']) }}">🖇️ Evidencias</a>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <!-- Ver Proyectos -->
        <div class="tab-pane fade {{ $tab === 'ver' ? 'show active' : '' }}" id="ver" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Año</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($proyectos as $proyecto)
                            <tr>
                                <td>{{ $proyecto['nombre'] }}</td>
                                <td>{{ $proyecto['descripcion'] }}</td>
                                <td>{{ $proyecto['anio'] }}</td>
                                <td>{{ $proyecto['estado'] }}</td>
                                <td>
                                    <a href="{{ route('gestor.proyectos.index', ['tab' => 'seguimiento', 'id' => $proyecto['_id'] ?? '']) }}" class="btn btn-sm btn-info">Ver Seguimiento</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">No hay proyectos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Crear Proyecto -->
        <div class="tab-pane fade {{ $tab === 'crear' ? 'show active' : '' }}" id="crear" role="tabpanel">
            <form method="POST" action="{{ route('gestor.proyectos.store') }}">
                @csrf
                <div class="form-group">
                    <label>Nombre del Proyecto</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea class="form-control" name="descripcion" required></textarea>
                </div>
                <div class="form-group">
                    <label>Año de Ejecución</label>
                    <input type="number" class="form-control" name="anio" required>
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select class="form-control" name="estado" required>
                        <option value="En planificación">En planificación</option>
                        <option value="En progreso">En progreso</option>
                        <option value="Finalizado">Finalizado</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Guardar Proyecto</button>
            </form>
        </div>

        <!-- Seguimiento -->
<div class="tab-pane fade {{ $tab === 'seguimiento' ? 'show active' : '' }}" id="seguimiento" role="tabpanel">
    @if(isset($proyectoSeleccionado))
        <h3>📊 Seguimiento del Proyecto: {{ $proyectoSeleccionado['nombre'] }}</h3>

        @php
    $total = 0;
    foreach ($seguimientos as $s) {
        $total += $s['avance'] ?? 0;
    }

    $claseColor = 'info';
    if ($total >= 100) {
        $claseColor = 'success';
    } elseif ($total >= 50) {
        $claseColor = 'warning';
    } else {
        $claseColor = 'danger';
    }
@endphp

<div class="alert alert-{{ $claseColor }}">
    <strong>Progreso Total del Proyecto:</strong> {{ $total }}%
    @if($total >= 100)
        <br><span class="font-weight-bold text-success">¡Proyecto Finalizado!</span>
    @endif
</div>

        @if(!empty($seguimientos) && is_array($seguimientos) && count($seguimientos) > 0)
            <table class="table table-bordered mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Avance (%)</th>
                        <th>Comentario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($seguimientos as $seg)
                        <tr>
                            <td>{{ $seg['fecha'] ?? '' }}</td>
                            <td>{{ $seg['avance'] ?? 0 }}%</td>
                            <td>{{ $seg['comentario'] ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-warning">Este proyecto no tiene seguimientos registrados.</div>
        @endif
    @else
        <div class="alert alert-info">Seleccione un proyecto desde la pestaña "Ver Proyectos" para ver su seguimiento.</div>
    @endif
</div>

        <!-- Evidencias -->
        <div class="tab-pane fade {{ $tab === 'evidencias' ? 'show active' : '' }}" id="evidencias" role="tabpanel">
            <h3>🖇️ Subir Evidencia de Proyecto</h3>

            <form action="{{ route('gestor.proyectos.evidencias.guardar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Seleccionar Proyecto</label>
                    <select class="form-control" name="proyecto_id" required>
                        <option value="">-- Seleccione --</option>
                        @foreach($proyectos as $proyecto)
                            <option value="{{ $proyecto['_id'] }}">{{ $proyecto['nombre'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Comentario</label>
                    <textarea class="form-control" name="comentario" required></textarea>
                </div>
                <div class="form-group">
                    <label>Avance (%)</label>
                    <input type="range" class="form-control-range" name="avance" min="0" max="100" step="1" oninput="this.nextElementSibling.value = this.value">
                    <output>0</output>%
                </div>
                <div class="form-group">
                    <label>Archivo Evidencia</label>
                    <input type="file" class="form-control-file" name="archivo">
                </div>
                <button type="submit" class="btn btn-primary">Subir Evidencia y Avance</button>
            </form>
        </div>
    </div>
</div>
@endsection

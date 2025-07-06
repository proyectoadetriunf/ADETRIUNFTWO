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
            <a class="nav-link {{ $tab === 'asignar' ? 'active' : '' }}" href="{{ route('gestor.proyectos.index', ['tab' => 'asignar']) }}">✅ Asignar Proyecto</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'seguimiento' ? 'active' : '' }}" href="{{ route('gestor.proyectos.index', ['tab' => 'seguimiento']) }}">📊 Seguimiento</a>
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
                        <option value="Planificación">Planificación</option>
                        <option value="Aprobado">Aprobado</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Guardar Proyecto</button>
            </form>
        </div>

        <!-- Asignar Proyecto -->
        <div class="tab-pane fade {{ $tab === 'asignar' ? 'show active' : '' }}" id="asignar" role="tabpanel">
            <h3>✅ Asignar Proyecto a Moderador</h3>
            <table class="table table-bordered mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>Nombre del Proyecto</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proyectosNoAsignados as $proyecto)
                        <tr>
                            <td>{{ $proyecto['nombre'] }}</td>
                            <td>
                                <form method="POST" action="{{ route('gestor.proyectos.asignar') }}">
                                    @csrf
                                    <input type="hidden" name="proyecto_id" value="{{ $proyecto['_id'] }}">
                                    <div class="form-row align-items-center">
                                        <div class="col-auto">
                                            <select name="moderador_id" class="form-control" required>
                                                <option value="">-- Seleccione Moderador --</option>
                                                @foreach($moderadores as $moderador)
                                                    <option value="{{ $moderador['_id'] }}">{{ $moderador['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary btn-sm">Asignar</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center">Todos los proyectos están asignados.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <h4 class="mt-5">📋 Proyectos Asignados</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Fecha de Asignación</th>
                        <th>Moderador Asignado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asignaciones as $asignacion)
                        <tr>
                            <td>{{ $asignacion['proyecto_nombre'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($asignacion['fecha_asignacion'])->format('d/m/Y') }}</td>
                            <td>{{ $asignacion['moderador_nombre'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">No hay asignaciones registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
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
    </div>
</div>
@endsection

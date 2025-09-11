@extends('adminlte::page')

@section('title', '📋 Actividades / Tareas')

@section('content')
<div class="container">
    <h1 class="mb-4">📋 Actividades / Tareas</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Formulario para registrar tarea -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Registrar Nueva Tarea
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.tareas.guardar') }}">
                @csrf
                <div class="form-group">
                    <label for="proyecto_id">Proyecto</label>
                    <select class="form-control" name="proyecto_id" required>
                        <option value="">-- Seleccione un proyecto --</option>
                        @foreach($proyectos as $proyecto)
                            <option value="{{ $proyecto['_id'] }}">{{ $proyecto['nombre'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" class="form-control" name="titulo" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control" name="descripcion" rows="2" required></textarea>
                </div>
                <div class="form-group">
                    <label for="etapa">Etapa</label>
                    <input type="text" class="form-control" name="etapa" required>
                </div>
                <div class="form-group">
                    <label for="moderador_id">Moderador a asignar</label>
                    <select class="form-control" name="moderador_id" required>
                        <option value="">-- Seleccione un moderador --</option>
                        @foreach($moderadores as $moderador)
                            <option value="{{ $moderador['_id'] }}">{{ $moderador['name'] }} ({{ $moderador['rol_id'] == 2 ? 'Técnico' : 'Moderador' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select class="form-control" name="estado" required>
                        <option value="Pendiente">Pendiente</option>
                        <option value="En proceso">En proceso</option>
                        <option value="Completada">Completada</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fecha_inicio">Fecha de Inicio</label>
                    <input type="date" class="form-control" name="fecha_inicio" required>
                </div>
                <div class="form-group">
                    <label for="fecha_fin">Fecha de Finalización</label>
                    <input type="date" class="form-control" name="fecha_fin" required>
                </div>
                <button type="submit" class="btn btn-success">Guardar Tarea</button>
            </form>
        </div>
    </div>

    <!-- Tabla de tareas existentes -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            Tareas Registradas
        </div>
        <div class="card-body table-responsive">
            @if(count($tareas) > 0)
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
    <tr>
        <th>Proyecto</th>
        <th>Título</th>
        <th>Etapa</th>
        <th>Descripción</th>
        <th>Moderador</th>
        <th>Estado</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Fecha/Hora Creación</th>
        <th>Acciones</th>
    </tr>
</thead>

                    <tbody>
                        @foreach($tareas as $tarea)
                            <tr>
                                <td>{{ $tarea['proyecto'] }}</td>
                                <td>{{ $tarea['titulo'] }}</td>
                                <td>{{ $tarea['etapa'] }}</td>
                                <td>{{ $tarea['descripcion'] }}</td>
                                <td>{{ $tarea['moderador'] ?? 'No asignado' }}</td>
                                <td>
                                    @if($tarea['estado'] === 'Pendiente')
                                        <span class="badge badge-warning">{{ $tarea['estado'] }}</span>
                                    @elseif($tarea['estado'] === 'En proceso')
                                        <span class="badge badge-primary">{{ $tarea['estado'] }}</span>
                                    @else
                                        <span class="badge badge-success">{{ $tarea['estado'] }}</span>
                                    @endif
                                </td>
                                <td>{{ $tarea['fecha_inicio'] }}</td>
                                <td>{{ $tarea['fecha_fin'] }}</td>
                                <td>
                                    <small class="text-muted">
                                        {{ $tarea['fecha_hora_creacion'] ?? 'No disponible' }}
                                    </small>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.tareas.eliminar', $tarea['_id']) }}" onsubmit="return confirm('¿Estás seguro de eliminar esta tarea?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning">No hay tareas registradas.</div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('adminlte::page')

@section('title', 'Cronograma de Proyecto')

@section('content')
<div class="container">
    <h1 class="mb-4">🗓️ Cronograma del Proyecto: <strong>{{ $proyecto['nombre'] ?? 'Proyecto Desconocido' }}</strong></h1>

    <!-- Formulario para agregar actividad -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            ➕ Agregar Actividad
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('gestor.proyectos.cronograma.store') }}">
                @csrf
                <input type="hidden" name="proyecto_id" value="{{ $proyecto_id }}">

                <div class="form-group">
                    <label for="actividad">Nombre de la Actividad</label>
                    <input type="text" name="actividad" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Fecha de Inicio</label>
                        <input type="date" name="inicio" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Fecha de Finalización</label>
                        <input type="date" name="fin" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Guardar Actividad</button>
            </form>
        </div>
    </div>

    <!-- Listado de actividades -->
    <div class="card">
        <div class="card-header bg-info text-white">
            📋 Actividades Programadas
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Actividad</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actividades as $actividad)
                        <tr>
                            <td>{{ $actividad['actividad'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($actividad['inicio'])->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($actividad['fin'])->format('d/m/Y') }}</td>
                            <td>
                                @if(!empty($actividad['completado']))
                                    <span class="text-success">✅ Completada</span>
                                @else
                                    <span class="text-warning">🕒 En Proceso</span>
                                @endif
                            </td>
                            <td>
                                @if(empty($actividad['completado']))
                                    <form method="POST" action="{{ route('gestor.proyectos.cronograma.finalizar', ['id' => $actividad['_id']]) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-success" onclick="return confirm('¿Marcar como finalizada esta actividad?')">Finalizar</button>
                                    </form>
                                @else
                                    <span class="text-muted">---</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No hay actividades registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

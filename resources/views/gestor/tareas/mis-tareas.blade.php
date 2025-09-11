@extends('adminlte::page')

@section('title', '📌 Mis Tareas Asignadas')

@section('content')
<div class="container">
    <h1 class="mb-4">📌 Tareas Asignadas</h1>

    @if(count($tareas) === 0)
        <div class="alert alert-warning">No tienes tareas asignadas por ahora.</div>
    @else
        <div class="card shadow">
            <div class="card-header bg-info text-white">Listado de Tareas</div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Proyecto</th>
                            <th>Título</th>
                            <th>Etapa</th>
                            <th>Estado</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tareas as $tarea)
                            <tr>
                                <td>{{ $tarea['proyecto'] }}</td>
                                <td>{{ $tarea['titulo'] }}</td>
                                <td>{{ $tarea['etapa'] }}</td>
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
                                    <!-- Botón Finalizar -->
                                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#finalizarModal{{ $tarea['_id'] }}">
                                        Finalizar
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="finalizarModal{{ $tarea['_id'] }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <form method="POST" action="{{ route('gestor.tareas.finalizar', $tarea['_id']) }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Subir Evidencia</h5>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Archivo de Evidencia (PDF, imagen, etc.)</label>
                                                            <input type="file" name="evidencia" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Finalizar Tarea</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection

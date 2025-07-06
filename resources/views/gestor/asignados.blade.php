@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>📁 Proyectos Asignados</h1>
    @if($proyectos->count() === 0)
        <div class="alert alert-warning mt-4">No tienes proyectos asignados actualmente.</div>
    @endif
    @foreach($proyectos as $proyecto)
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                {{ $proyecto['nombre'] ?? 'Proyecto' }}
            </div>
            <div class="card-body">
                <p><strong>Descripción:</strong> {{ $proyecto['descripcion'] ?? '' }}</p>
                <p><strong>Año:</strong> {{ $proyecto['anio'] ?? '' }}</p>
                <p><strong>Estado:</strong> {{ $proyecto['estado'] ?? '' }}</p>
                <hr>
                <h5>Subir Evidencia</h5>
                <form action="{{ route('gestor.evidencia.subir', $proyecto['_id']) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="evidencia" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <button type="submit" class="btn btn-primary btn-sm">Subir</button>
                </form>
                <hr>
                <h5>Evidencias</h5>
                @if(isset($proyecto['evidencias']) && count($proyecto['evidencias']) > 0)
                    <ul>
                        @foreach($proyecto['evidencias'] as $ev)
                            <li class="d-flex align-items-center justify-content-between">
                                <span>
                                    <a href="{{ route('gestor.evidencia.descargar', [$proyecto['_id'], $ev['archivo']]) }}" target="_blank">
                                        {{ $ev['nombre'] ?? $ev['archivo'] }}
                                    </a>
                                </span>
                                <form action="{{ route('gestor.evidencia.eliminar', [$proyecto['_id'], $ev['archivo']]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta evidencia?')">Eliminar</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <span class="text-muted">No hay evidencias subidas.</span>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection

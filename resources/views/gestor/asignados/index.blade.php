@extends('adminlte::page')

@section('title', 'Proyectos Asignados')

@section('content')
<div class="container mt-4">
    <h1>📋 Proyectos Asignados</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre del Proyecto</th>
                    <th>Descripción</th>
                    <th>Año</th>
                    <th>Costo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($proyectosAsignados as $proyecto)
                    <tr>
                        <td>{{ $proyecto['nombre'] }}</td>
                        <td>{{ $proyecto['descripcion'] }}</td>
                        <td>{{ $proyecto['anio'] }}</td>
                        <td>L. {{ number_format($proyecto['costo'], 2) }}</td>
                        <td>{{ $proyecto['estado'] }}</td>
                        <td>
                            <a href="{{ route('gestor.cronograma', ['id' => $proyecto['_id']]) }}" class="btn btn-primary btn-sm">📅 Gestión de Proyecto</a>
                            <a href="{{ route('gestor.avances', ['id' => $proyecto['_id']]) }}" class="btn btn-success btn-sm">📈 Avances del Proyecto</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay proyectos asignados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

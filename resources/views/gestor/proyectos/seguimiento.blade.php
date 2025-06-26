@extends('adminlte::page')

@section('title', 'Seguimiento del Proyecto')

@section('content')
<div class="container">
    <h2>📊 Seguimiento de: {{ $proyecto['nombre'] }}</h2>

    @if(count($seguimientos) > 0)
        <table class="table table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Avance (%)</th>
                    <th>Comentario</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($seguimientos as $seg)
                    <tr>
                        <td>{{ $seg['fecha'] }}</td>
                        <td>{{ $seg['avance'] }}%</td>
                        <td>{{ $seg['comentario'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-warning">No hay seguimientos registrados.</div>
    @endif

    <a href="{{ route('gestor.proyectos.index', ['tab' => 'ver']) }}" class="btn btn-secondary mt-3">⬅️ Volver</a>
</div>
<td>
    <a href="{{ route('gestor.proyectos.verSeguimientos', ['id' => $proyecto['_id'] ?? '']) }}" class="btn btn-sm btn-info">
        Ver Seguimiento
    </a>
</td>

@endsection

@extends('adminlte::page')

@section('title', 'Lista de Beneficiarios')

@section('content')
<div class="container mt-4">
    <h1>🧍 Lista de Beneficiarios por Proyecto</h1>

    @foreach($proyectos as $proyecto)
        @php
            $benefPorProyecto = $beneficiarios->filter(fn($b) => $b['proyecto_id'] == (string) $proyecto['_id']);
        @endphp

        @if($benefPorProyecto->count())
            <div class="card my-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $proyecto['nombre'] ?? 'Proyecto sin nombre' }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>DNI</th>
                                    <th>Teléfono</th>
                                    <th>Correo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($benefPorProyecto as $b)
                                    <tr>
                                        <td>{{ $b['nombre'] }}</td>
                                        <td>{{ $b['dni'] }}</td>
                                        <td>{{ $b['telefono'] }}</td>
                                        <td>{{ $b['correo'] }}</td>
                                        <td>
                                            <a href="{{ route('beneficiarios.encuesta', $b['_id']) }}" class="btn btn-success btn-sm">
                                                📝 Encuesta
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    @if($proyectos->isEmpty())
        <div class="alert alert-warning text-center">No hay proyectos disponibles.</div>
    @endif
</div>
@endsection

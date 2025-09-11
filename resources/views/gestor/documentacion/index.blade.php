@extends('adminlte::page')

@section('title', '📁 Evidencia y Documentación')

@section('content')
<div class="container mt-4">
    <h1>📁 Evidencia y Documentación</h1>

    @foreach($beneficiarios as $b)
        @if(!empty($b['control']))
            <div class="card my-4">
                <div class="card-header bg-info text-white">
                    <strong>{{ $b['nombre'] }}</strong> - {{ $b['proyecto'] }}
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped m-0">
                        <thead>
                            <tr>
                                <th>Pregunta</th>
                                <th>Respuesta</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($b['control'] as $registro)
                                <tr>
                                    <td>{{ $registro['pregunta'] }}</td>
                                    <td>{{ $registro['respuesta'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($registro['fecha'])->format('d/m/Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach

    @if($beneficiarios->filter(fn($b) => !empty($b['control']))->isEmpty())
        <div class="alert alert-warning text-center">No hay encuestas registradas.</div>
    @endif
</div>
@endsection

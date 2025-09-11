@extends('adminlte::page')

@section('title', '📚 Evidencia y Documentación')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📚 Encuestas Registradas</h2>

    @foreach($beneficiarios as $b)
        @if(!empty($b['control']))
            <div class="card mb-4 shadow">
                <div class="card-header bg-info text-white">
                    <strong>{{ $b['nombre'] }}</strong> - <em>{{ $b['proyecto'] }}</em>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Pregunta</th>
                                <th>Respuesta</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($b['control'] as $item)
                                <tr>
                                    <td>{{ $item['pregunta'] }}</td>
                                    <td>{{ $item['respuesta'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item['fecha'])->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach

    @if(collect($beneficiarios)->filter(fn($b) => !empty($b['control']))->isEmpty())
        <div class="alert alert-warning text-center">No hay encuestas registradas aún.</div>
    @endif
</div>
@endsection

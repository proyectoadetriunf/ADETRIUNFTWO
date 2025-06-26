@extends('adminlte::page')

@section('title', 'Beneficiarios')

@section('content')
<div class="container mt-4">
    <h1>🧍 Lista de Beneficiarios</h1>

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($beneficiarios as $item)
                    <tr>
                        <td>{{ $item['nombre'] }}</td>
                        <td>{{ $item['dni'] }}</td>
                        <td>{{ $item['telefono'] }}</td>
                        <td>{{ $item['correo'] }}</td>
                        <td>
                            <a href="{{ route('beneficiarios.encuesta', $item['_id']) }}" class="btn btn-success btn-sm">
                                📝 Encuesta
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay beneficiarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

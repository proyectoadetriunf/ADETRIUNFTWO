@extends('adminlte::page')

@section('title', 'Listado de Beneficiarios')

@section('content_header')
    <h1>Listado de Beneficiarios</h1>
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nombre completo</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Proyecto</th>
            <th>Departamento</th>
            <th>Municipio</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($beneficiarios as $b)
            <tr>
                <td>{{ $b->nomb_per }}</td>
                <td>{{ $b->dni }}</td>
                <td>{{ $b->telefono }}</td>
                <td>{{ $b->proyecto->nombre_proyecto ?? 'N/A' }}</td>
                <td>{{ $b->departamento->nombre_depto ?? 'N/A' }}</td>
                <td>{{ $b->municipio->nombre_municipio ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('beneficiarios.edit', $b->persona_id) }}" class="btn btn-sm btn-warning">Editar</a>

                    <form action="{{ route('beneficiarios.destroy', $b->persona_id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Está seguro de eliminar este beneficiario?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection

@extends('adminlte::page')

@section('title', 'Editar Costo del Proyecto')

@section('content')
<div class="container">
    <h1>✏️ Editar Costo del Proyecto</h1>

    <form action="{{ route('gestor.proyectos.actualizar', ['id' => $proyecto['_id']]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nombre del Proyecto</label>
            <input type="text" class="form-control" value="{{ $proyecto['nombre'] ?? '' }}" disabled>
        </div>

        <div class="form-group">
            <label>Costo del Proyecto</label>
            <input type="number" name="costo" class="form-control" step="0.01" value="{{ $proyecto['costo'] ?? 0 }}" required>
        </div>

        <input type="hidden" name="nombre" value="{{ $proyecto['nombre'] ?? '' }}">
        <input type="hidden" name="descripcion" value="{{ $proyecto['descripcion'] ?? '' }}">
        <input type="hidden" name="anio" value="{{ $proyecto['anio'] ?? '' }}">
        <input type="hidden" name="estado" value="{{ $proyecto['estado'] ?? 'Aprobado' }}">

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{{ route('gestor.proyectos.index', ['tab' => 'ver']) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

@extends('adminlte::page')

@section('title', 'Solicitud de Nuevo Proyecto')

@section('content')
<div class="container">
    <h1 class="mb-4">📝 Solicitud de Nuevo Proyecto</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('solicitudes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Proyecto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="anio" class="form-label">Año de Ejecución</label>
            <input type="number" class="form-control" id="anio" name="anio" min="2024" required>
        </div>

        <div class="mb-3">
            <label for="costo" class="form-label">Costo del Proyecto (Lps.)</label>
            <input type="number" class="form-control" id="costo" name="costo" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="admin_id" class="form-label">Enviar solicitud a:</label>
            <select class="form-control" name="admin_id" id="admin_id" required>
                <option value="">Seleccione un administrador</option>
                @foreach($administradores as $admin)
                    <option value="{{ $admin['_id'] }}">{{ $admin['name'] }} ({{ $admin['email'] }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <input type="text" class="form-control" id="estado" name="estado" value="Pendiente" readonly>
        </div>

        <button type="submit" class="btn btn-success">📤 Enviar Solicitud</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">↩️ Cancelar</a>
    </form>
</div>
@endsection

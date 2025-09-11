@extends('adminlte::page')

@section('title', 'Editar Beneficiario')

@section('content_header')
    <h1>Editar Beneficiario</h1>
@stop

@section('content')
<form action="{{ route('beneficiarios.update', $beneficiario['_id']) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nomb_per" class="form-control" value="{{ $persona['nombres'] ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>DNI</label>
                <input type="text" name="dni" class="form-control" value="{{ $persona['dni'] ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $persona['fecha_nacimiento'] ?? '' }}">
            </div>

            <div class="form-group">
                <label>Sexo</label>
                <select name="sexo" class="form-control">
                    <option value="Masculino" {{ ($persona['sexo'] ?? '') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="Femenino" {{ ($persona['sexo'] ?? '') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                </select>
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ $persona['telefono'] ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>Correo</label>
                <input type="email" name="correo" class="form-control" value="{{ $persona['correo'] ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="direccion" class="form-control" value="{{ $persona['direccion'] ?? '' }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Departamento</label>
                <select name="departamento_id" class="form-control">
                    @foreach($departamentos as $depto)
                        <option value="{{ $depto['_id'] }}" {{ $beneficiario['departamento_id'] == $depto['_id'] ? 'selected' : '' }}>
                            {{ $depto['nombre_depto'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Municipio</label>
                <select name="municipio_id" class="form-control">
                    @foreach($municipios as $muni)
                        <option value="{{ $muni['_id'] }}" {{ $beneficiario['municipio_id'] == $muni['_id'] ? 'selected' : '' }}>
                            {{ $muni['nombre_muni'] }}
                        </option>
                    @endforeach
                </select>
            </div><div class="form-group">
    <label>Colonia</label>
    <select name="colonia_id" class="form-control">
        @foreach($colonias as $col)
            <option value="{{ $col['colonia_id'] }}"
                {{ $beneficiario['colonia_id'] == $col['colonia_id'] ? 'selected' : '' }}>
                {{ $col['nombre_col'] ?? 'Sin nombre' }}
            </option>
        @endforeach
    </select>
</div>

        </div>
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="{{ route('beneficiarios.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@stop

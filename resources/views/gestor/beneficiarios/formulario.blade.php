@extends('adminlte::page')

@section('title', 'Registrar Beneficiario')

@section('content')
<div class="container-fluid">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Registrar Beneficiario</h3>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>¡Ups!</strong> Hay algunos problemas con los datos ingresados.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('beneficiarios.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="proyecto_id" value="{{ $proyecto_id ?? '' }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nombre completo</label>
                        <input type="text" name="nomb_per" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>DNI</label>
                        <input type="text" name="dni" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Sexo</label>
                        <select name="sexo" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Correo electrónico</label>
                        <input type="email" name="correo" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Departamento</label>
                        <select name="departamento_id" id="departamento" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto['departamento_id'] ?? $depto['_id'] }}">
                                    {{ $depto['nombre_depto'] ?? $depto['nombre'] ?? 'Sin nombre' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Municipio</label>
                        <select name="municipio_id" id="municipio" class="form-control" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Colonia</label>
                        <select name="colonia_id" id="colonia" class="form-control" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Técnico asignado</label>
                        <select name="tecnico_id" class="form-control" required>
                            <option value="">Seleccione</option>
                            @foreach($tecnicos as $tec)
                                <option value="{{ $tec['_id'] }}">{{ $tec['nombre'] ?? $tec['nombres'] ?? 'Sin nombre' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Archivo (opcional)</label>
                        <input type="file" name="archivo" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Guardar Beneficiario</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const departamento = document.getElementById('departamento');
    const municipio = document.getElementById('municipio');
    const colonia = document.getElementById('colonia');

    departamento.addEventListener('change', () => {
        fetch(`/api/municipios/${departamento.value}`)
            .then(res => res.json())
            .then(data => {
                municipio.innerHTML = '<option value="">Seleccione</option>';
                data.forEach(item => {
                    municipio.innerHTML += `<option value="${item.municipio_id ?? item._id}">${item.nombre_muni}</option>`;
                });
                colonia.innerHTML = '<option value="">Seleccione</option>';
            }).catch(err => {
                alert('Error al cargar municipios');
                console.error(err);
            });
    });

    municipio.addEventListener('change', () => {
        fetch(`/api/colonias/${municipio.value}`)
            .then(res => res.json())
            .then(data => {
                colonia.innerHTML = '<option value="">Seleccione</option>';
                data.forEach(item => {
                    colonia.innerHTML += `<option value="${item.colonia_id ?? item._id}">${item.nombre_col}</option>`;
                });
            }).catch(err => {
                alert('Error al cargar colonias');
                console.error(err);
            });
    });
});
</script>
@endsection

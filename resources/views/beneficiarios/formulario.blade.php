@extends('adminlte::page')

@section('title', 'Registrar Beneficiario')

@section('content')
<div class="container-fluid">
    <h3>Registrar Beneficiario</h3>

    {{-- Mostrar errores de validación --}}
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

    {{-- Éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Formulario --}}
    <form method="POST" action="{{ route('beneficiarios.store') }}">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nombre completo</label>
                <input type="text" name="nomb_per" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>DNI</label>
                <input type="text" name="dni" class="form-control" required placeholder="0000-0000-00000">
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
                <input type="text" name="telefono" class="form-control" required placeholder="0000-0000">
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
                        <option value="{{ (int) $depto->departamento_id }}">{{ $depto->nombre_depto }}</option>
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
                        <option value="{{ $tec['_id'] }}">{{ $tec['nombres'] ?? 'Sin nombre' }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Beneficiario</button>
    </form>

    <hr>
    <h4>Listado de Beneficiarios</h4>

    <table class="table table-bordered table-striped mt-3">
        <thead class="bg-primary text-white">
            <tr>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Dirección</th>
                <th>Departamento</th>
                <th>Municipio</th>
                <th>Colonia</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($beneficiarios as $b)
                <tr>
                    <td>{{ $b['nomb_per'] }}</td>
                    <td>{{ $b['dni'] }}</td>
                    <td>{{ $b['telefono'] }}</td>
                    <td>{{ $b['correo'] }}</td>
                    <td>{{ $b['direccion'] }}</td>
                    <td>{{ $b['nombre_departamento'] }}</td>
                    <td>{{ $b['nombre_municipio'] }}</td>
                    <td>{{ $b['nombre_colonia'] }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">Ver</a>
                        <a href="#" class="btn btn-sm btn-warning">Editar</a>
                        <form method="POST" action="#" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este beneficiario?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No hay beneficiarios registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('js')
<script>
    // Cargar municipios por departamento
    document.getElementById('departamento').addEventListener('change', function () {
        const departamentoId = this.value;
        fetch(`/api/municipios/${departamentoId}`)
            .then(response => response.json())
            .then(data => {
                const municipioSelect = document.getElementById('municipio');
                municipioSelect.innerHTML = '<option value="">Seleccione</option>';
                data.forEach(item => {
                    municipioSelect.innerHTML += `<option value="${item.municipio_id}">${item.nombre_muni}</option>`;
                });
                document.getElementById('colonia').innerHTML = '<option value="">Seleccione</option>';
            });
    });

    // Cargar colonias por municipio
    document.getElementById('municipio').addEventListener('change', function () {
        const municipioId = this.value;
        fetch(`/api/colonias/${municipioId}`)
            .then(response => response.json())
            .then(data => {
                const coloniaSelect = document.getElementById('colonia');
                coloniaSelect.innerHTML = '<option value="">Seleccione</option>';
                data.forEach(item => {
                    coloniaSelect.innerHTML += `<option value="${item.colonia_id}">${item.nombre_col}</option>`;
                });
            });
    });
</script>
@endsection

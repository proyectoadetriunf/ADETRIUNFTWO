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
                                <option value="{{ $tec['_id'] }}">{{ $tec['nombre'] ?? $tec['nombres'] ?? 'Sin nombre' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Archivo (opcional)</label>
                        <input type="file" name="archivo" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="mirame" id="mirame">
                            <label class="form-check-label" for="mirame">Mírame</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Beneficiario</button>
            </form>
        </div>
    </div>

    {{-- Tabla de beneficiarios --}}
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Beneficiario ya registrado en el sistema</h3>
            <div class="input-group" style="width: 200px;">
                <input type="text" class="form-control" placeholder="Buscar">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover">
                <thead class="thead-light">
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
                            <td>{{ $b['nombre_departamento'] ?? 'N/A' }}</td>
                            <td>{{ $b['nombre_municipio'] ?? 'N/A' }}</td>
                            <td>{{ $b['nombre_colonia'] ?? 'N/A' }}</td>
                            <td>
                                @if(!empty($b['archivo_identidad']))
                                    <a href="{{ asset('storage/' . $b['archivo_identidad']) }}" target="_blank" class="btn btn-sm btn-secondary mb-1">Ver identidad</a>
                                @endif
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
    </div>

    {{-- Gráfico --}}
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-bar"></i> Gráfico de barras</h3>
        </div>
        <div class="card-body">
            <canvas id="graficoMeses" height="100"></canvas>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const etiquetas = @json($meses ?? []);
    const datos = @json($valores ?? []);

    const ctx = document.getElementById('graficoMeses').getContext('2d');
    const grafico = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: etiquetas,
            datasets: [{
                label: 'Beneficiarios registrados',
                data: datos,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

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

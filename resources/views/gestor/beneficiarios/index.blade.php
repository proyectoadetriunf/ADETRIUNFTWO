@extends('adminlte::page')

@section('title', 'Beneficiarios por Proyecto')

@section('content')
<div class="container mt-4">
    <h1>🗂️ Beneficiarios por Proyecto</h1>

    @foreach($proyectos as $proyecto)
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 d-flex justify-content-between align-items-center">
                    <span>📌 {{ $proyecto['nombre'] ?? 'Sin nombre' }}</span>
                    <button class="btn btn-light btn-sm" onclick="toggleBeneficiarios('{{ $proyecto['_id'] }}')">
                        👁️ Ver Beneficiarios
                    </button>
                </h5>
            </div>
            <div class="card-body" id="beneficiarios-{{ $proyecto['_id'] }}" style="display: none;">
                @php
                    $beneficiariosProyecto = $beneficiarios->filter(function ($b) use ($proyecto) {
                        return isset($b['proyecto_id']) && (string) $b['proyecto_id'] === (string) $proyecto['_id'];
                    });
                @endphp

                @if($beneficiariosProyecto->isEmpty())
                    <p>No hay beneficiarios registrados para este proyecto.</p>
                @else
                    <div class="table-responsive">
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
                                @foreach($beneficiariosProyecto as $item)
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

<script>
    function toggleBeneficiarios(id) {
        const elem = document.getElementById('beneficiarios-' + id);
        if (elem.style.display === 'none') {
            elem.style.display = 'block';
        } else {
            elem.style.display = 'none';
        }
    }
</script>
@endsection

@extends('adminlte::page')

@section('title', 'Agregar Beneficiario')

@section('content')
<div class="container mt-4">
    <h2><i class="fas fa-file-alt text-warning"></i> Agregar Beneficiario</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead class="thead-dark">
            <tr>
                <th>Nombre del Proyecto</th>
                <th>Descripción</th>
                <th>Año</th>
                <th>Costo</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($proyectosAsignados as $proyecto)
                <tr>
                    <td>{{ $proyecto['nombre'] ?? 'Sin nombre' }}</td>
                    <td>{{ $proyecto['descripcion'] ?? 'N/D' }}</td>
                    <td>{{ $proyecto['anio'] ?? 'N/D' }}</td>
                    <td>L. {{ number_format($proyecto['costo'] ?? 0, 2) }}</td>
                    <td>{{ $proyecto['estado'] ?? 'N/D' }}</td>
                    <td>
                        <button class="btn btn-success" onclick="mostrarFormulario(this, '{{ $proyecto['_id'] }}')">
                            <i class="fas fa-plus text-purple"></i> Agregar Beneficiarios
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay proyectos asignados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Formulario cargado dinámicamente -->
    <div id="formulario-container" class="mt-4"></div>
</div>
@endsection

@section('js')
<script>
function mostrarFormulario(btn, proyectoId) {
    // Desactiva el botón mientras carga
    btn.disabled = true;
    btn.innerHTML = 'Cargando...';

    // Limpia el contenedor antes de insertar nuevo contenido
    const contenedor = document.getElementById('formulario-container');
    contenedor.innerHTML = '';

    fetch(`/beneficiarios/formulario/${proyectoId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener el formulario');
            }
            return response.text();
        })
        .then(html => {
            contenedor.innerHTML = html;
            contenedor.scrollIntoView({ behavior: 'smooth' });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al cargar el formulario');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus text-purple"></i> Agregar Beneficiarios';
        });
}
</script>
@endsection
x|
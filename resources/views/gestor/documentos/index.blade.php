@extends('adminlte::page')

@section('title', '📁 Evidencia y Documentación')

@section('content')
<div class="container">
    <h1 class="mb-4">📁 Evidencia y Documentación</h1>

    <!-- Pestañas -->
    <ul class="nav nav-tabs" id="tabsDocumentacion" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="evidencia-tab" data-bs-toggle="tab" href="#evidencia" role="tab">🖼️ Evidencia</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="documentacion-tab" data-bs-toggle="tab" href="#documentacion" role="tab">📑 Documentación</a>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content mt-3" id="tabsContentDocumentacion">

        <!-- Tab: Evidencia -->
        <div class="tab-pane fade show active" id="evidencia" role="tabpanel">
            <div class="row">
                @forelse($imagenes as $imagen)
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm">
                            <img src="{{ asset('storage/' . $imagen['ruta']) }}" class="card-img-top" alt="Evidencia">
                            <div class="card-body text-center">
                                <a href="{{ asset('storage/' . $imagen['ruta']) }}" class="btn btn-primary btn-sm" download>
                                    📥 Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">No se encontraron evidencias.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Tab: Documentación -->
        <div class="tab-pane fade" id="documentacion" role="tabpanel">
            <h4>📝 Encuestas Registradas</h4>
            <div class="mb-3 text-end">
                <a href="{{ route('documentos.exportar', 'pdf') }}" class="btn btn-danger btn-sm">📄 Exportar PDF</a>
                <a href="{{ route('documentos.exportar', 'word') }}" class="btn btn-primary btn-sm">📝 Exportar Word</a>
                <a href="{{ route('documentos.exportar', 'excel') }}" class="btn btn-success btn-sm">📊 Exportar Excel</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>🧍 Beneficiario</th>
                            <th>📅 Fecha</th>
                            <th>❓ Pregunta</th>
                            <th>✅ Respuesta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($encuestas as $encuesta)
                            <tr>
                                <td>{{ $encuesta['beneficiario'] }}</td>
                                <td>{{ $encuesta['fecha'] }}</td>
                                <td>{{ $encuesta['pregunta'] }}</td>
                                <td>{{ $encuesta['respuesta'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No hay encuestas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@push('js')
<!-- Bootstrap Tabs (si es necesario) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush

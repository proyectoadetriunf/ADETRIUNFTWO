@extends('adminlte::page')
@section('title', 'Gestión de Beneficiarios')

@section('content')
<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-12 text-center">
      <h1 class="text-success">🌍 Comunidad: El Triunfo, Choluteca</h1>
      <p class="text-muted">Análisis de beneficiarios y proyectos asignados a esta región</p>
    </div>
  </div>

  <!-- Estadísticas generales -->
  <div class="row text-center">
    <div class="col-md-3 mb-3">
      <div class="card bg-primary text-white shadow">
        <div class="card-body">
          <h4>👥 Beneficiarios</h4>
          <h2>{{ $beneficiarios }}</h2>
          <p>Registrados en esta comunidad</p>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
  <div class="card bg-warning text-dark shadow">
    <div class="card-body">
      <h4>📁 Proyectos activos</h4>
      <h2>{{ $proyectosActivos }}</h2>
      <p>Actualmente en ejecución</p>
    </div>
  </div>
</div>


    <div class="col-md-3 mb-3">
      <div class="card bg-success text-white shadow">
        <div class="card-body">
          <h4>📊 Cumplimiento</h4>
          <h2>82%</h2>
          <p>Promedio de avance</p>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card bg-info text-white shadow">
        <div class="card-body">
          <h4>💸 Ingresos asignados</h4>
          <h2>L. 280,000</h2>
          <p>Total aportado por ONG</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Mapa con Leaflet -->
<div class="row mt-4">
  <div class="col-12">
    <div class="card shadow">
      <div class="card-header bg-dark text-white">
        🗺️ Mapa de proyectos en El Triunfo
      </div>
      <div class="card-body">
        <div id="mapa" style="height: 400px; border-radius: 10px;"></div>
      </div>
    </div>
  </div>
</div>

  <!-- Lista de proyectos -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card shadow">
        <div class="card-header bg-success text-white">
          📋 Proyectos registrados
        </div>
        <div class="card-body">
          @php
            $proyectosVisibles = $proyectos->filter(function($p) {
              return !empty($p['nombre']) ||
                     !empty($p['descripcion']) ||
                     !empty($p['año']) ||
                     !empty($p['avance']) ||
                     !empty($p['estado']);
            });
          @endphp

          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Año</th>
                <th>Avance</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($proyectosVisibles as $proyecto)
                <tr>
                  <td>{{ $proyecto['nombre'] ?? '-' }}</td>
                  <td>{{ $proyecto['descripcion'] ?? '-' }}</td>
                  <td>{{ $proyecto['anio'] ?? '-' }}</td>
                  <td>{{ $proyecto['avance_total'] ?? 0 }}%</td>
                  <td>
                    @php
                      $estado = $proyecto['estado'] ?? 'Desconocido';
                      $badge = 'secondary';
                      if ($estado === 'En progreso') $badge = 'success';
                      elseif ($estado === 'Casi finalizado') $badge = 'primary';
                      elseif ($estado === 'Detenido') $badge = 'danger';
                    @endphp
                    <span class="badge bg-{{ $badge }}">{{ $estado }}</span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">No hay proyectos con información disponible.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const mapa = L.map('mapa').setView([13.1006, -87.025], 13); // El Triunfo

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(mapa);

    // Proyectos desde el backend
    const proyectos = @json($proyectos);

    proyectos.forEach(p => {
      if (p.lat && p.lng && p.nombre) {
        L.marker([p.lat, p.lng]).addTo(mapa)
          .bindPopup(`<strong>${p.nombre}</strong><br>El Triunfo, Choluteca`);
      }
    });
  });
</script>


@endsection

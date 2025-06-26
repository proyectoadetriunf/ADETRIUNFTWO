@extends('adminlte::page')

@section('title', 'Gestión de Citas')

@php
    $tab = $tab ?? 'programadas';
@endphp

@section('content')
<div class="container">
    <h1>📋 Gestión de Citas</h1>

    <!-- Navegación -->
    <ul class="nav nav-tabs mt-3">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'programadas' ? 'active' : '' }}" href="{{ route('gestor.citas.index', ['tab' => 'programadas']) }}">
                📅 Citas Programadas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'agendar' ? 'active' : '' }}" href="{{ route('gestor.citas.index', ['tab' => 'agendar']) }}">
                📝 Agendar Cita
            </a>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <!-- Calendario -->
        <div class="tab-pane fade {{ $tab === 'programadas' ? 'show active' : '' }}" id="programadas" role="tabpanel">
            <div class="card p-3">
                <div id="calendar"></div>
            </div>
        </div>

        <!-- Agendar nueva cita -->
        <div class="tab-pane fade {{ $tab === 'agendar' ? 'show active' : '' }}" id="agendar" role="tabpanel">
            <form action="{{ route('gestor.citas.guardar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Nombre del Proyecto</label>
                    <select name="proyecto_id" class="form-control" required>
                        <option value="">Seleccione un proyecto</option>
                        @foreach($proyectos as $proyecto)
                            <option value="{{ $proyecto['_id'] }}">{{ $proyecto['nombre'] ?? 'Proyecto sin nombre' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label>Fecha de la Cita</label>
                    <input type="date" class="form-control" name="fecha" required>
                </div>
                <div class="form-group mt-3">
                    <label>Motivo</label>
                    <textarea class="form-control" name="motivo" rows="3" required></textarea>
                </div>
                <div class="form-group mt-3">
                    <label>Archivo Evidencia (opcional)</label>
                    <input type="file" class="form-control-file" name="archivo">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Guardar Cita</button>
            </form>
        </div>
    </div>
</div>

<!-- FullCalendar -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            events: @json($citasCalendar),
            eventClick: function(info) {
                const proyectoId = info.event.id;
                // Redirige a la vista de evidencias del proyecto
                window.location.href = '/gestor/proyectos?tab=evidencias&proyecto_id=' + proyectoId;
            }
        });
        calendar.render();
    });
</script>
@endsection

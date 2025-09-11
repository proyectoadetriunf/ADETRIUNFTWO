@extends('adminlte::page')

@section('title', '📅 Agenda y Gestión de Citas')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <!-- Calendario -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">📅 Citas en Calendario</h5>
                </div>
                <div class="card-body p-0">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Formulario de nueva cita -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📝 Agendar Nueva Cita</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('gestor.citas.guardar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Fecha de la Cita</label>
                            <input type="date" class="form-control" name="fecha" required>
                        </div>
                        <div class="form-group">
                            <label>Motivo</label>
                            <textarea name="motivo" rows="3" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Adjuntar Evidencia (opcional)</label>
                            <input type="file" name="archivo" class="form-control-file">
                        </div>
                        <button type="submit" class="btn btn-success btn-block mt-3">📥 Guardar Cita</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">📋 Historial de Citas</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Fecha</th>
                                <th>Motivo</th>
                                <th>Registrado por</th>
                                <th>Archivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($citas as $cita)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($cita['fecha'])->format('d/m/Y') }}</td>
                                    <td>{{ $cita['motivo'] }}</td>
                                    <td>{{ $cita['usuario'] ?? 'N/D' }}</td>
                                    <td>
                                        @if(isset($cita['archivo']))
                                            <a href="{{ asset('storage/' . $cita['archivo']) }}" target="_blank" class="btn btn-sm btn-outline-secondary">📎 Ver Archivo</a>
                                        @else
                                            <span class="text-muted">No adjunto</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">No hay citas registradas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar moderno con idioma español -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día'
            },
            events: @json($citasCalendar),
            eventClick: function(info) {
                alert('📌 ' + info.event.title + "\n🗓️ " + info.event.start.toLocaleDateString('es-ES') + "\n👤 Registrado por: " + (info.event.extendedProps.usuario || 'N/D'));
            }
        });
        calendar.render();
    });
</script>
@endsection

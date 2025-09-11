@extends('adminlte::page')

@section('title', '🏢 Gestión de Reservas del Salón')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <!-- Calendario -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">📅 Reservas del Salón</h5>
                </div>
                <div class="card-body p-0">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Formulario de reserva -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📝 Reservar el Salón</h5>
                </div>
                <div class="card-body">
                    <form id="formReserva" action="{{ route('gestor.salon.guardar') }}" method="POST">
                        @csrf
                        <div class="form-group mt-3">
                            <label>Fecha</label>
                            <input type="date" id="fechaReserva" class="form-control" name="fecha" required>
                        </div>
                        <div class="form-group mt-3">
                            <label>Hora de Inicio</label>
                            <input type="time" id="horaInicio" class="form-control" name="hora_inicio" required>
                        </div>
                        <div class="form-group mt-3">
                            <label>Hora de Fin</label>
                            <input type="time" id="horaFin" class="form-control" name="hora_fin" required>
                        </div>
                        <div class="form-group mt-3">
                            <label>Motivo</label>
                            <textarea name="motivo" rows="3" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-block mt-3">📥 Guardar Reserva</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Reservas -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">📋 Historial de Reservas</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Registrado por</th>
                                <th>Fecha</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Motivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservas as $reserva)
                                <tr>
                                    <td>{{ $reserva['usuario'] ?? 'N/D' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reserva['fecha'])->format('d/m/Y') }}</td>
                                    <td>{{ $reserva['hora_inicio'] }}</td>
                                    <td>{{ $reserva['hora_fin'] }}</td>
                                    <td>{{ $reserva['motivo'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No hay reservas registradas.</td></tr>
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
        const reservas = @json($reservasCalendar);

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
            events: reservas,
            eventClick: function(info) {
                const startDate = info.event.start;
                alert('📌 Reserva:\n👤 Registrado por: ' + info.event.title +
                      '\n🗓️ Fecha: ' + startDate.toLocaleDateString('es-ES') +
                      '\n🕒 Hora: ' + startDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) +
                      '\n📄 Motivo: ' + info.event.extendedProps.motivo);
            }
        });
        calendar.render();

        // Validación de conflicto
        document.getElementById('formReserva').addEventListener('submit', function(e) {
            const fecha = document.getElementById('fechaReserva').value;
            const horaInicio = document.getElementById('horaInicio').value.padStart(5, '0');
            const horaFin = document.getElementById('horaFin').value.padStart(5, '0');

            const conflicto = reservas.some(evento => {
                const [eventDate, eventTimeStart] = evento.start.split('T');
                const eventTimeEnd = evento.end ? evento.end.split('T')[1].slice(0,5) : eventTimeStart;

                return eventDate === fecha && (
                    (horaInicio >= eventTimeStart && horaInicio < eventTimeEnd) ||
                    (horaFin > eventTimeStart && horaFin <= eventTimeEnd) ||
                    (horaInicio <= eventTimeStart && horaFin >= eventTimeEnd)
                );
            });

            if (conflicto) {
                e.preventDefault();
                alert('⚠️ Ya existe una reserva en ese horario. Por favor elige otra.');
            }
        });
    });
</script>
@endsection

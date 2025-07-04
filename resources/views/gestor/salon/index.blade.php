@extends('adminlte::page')

@section('title', 'Gestión de Salón')

@php
    $tab = $tab ?? 'uso';
@endphp

@section('content')
<div class="container">
    <h1>🏢 Gestión de Reservas del Salón</h1>

    <!-- Navegación -->
    <ul class="nav nav-tabs mt-3">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'uso' ? 'active' : '' }}" href="{{ route('gestor.salon.index', ['tab' => 'uso']) }}">
                📅 Uso del Salón
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'reservar' ? 'active' : '' }}" href="{{ route('gestor.salon.index', ['tab' => 'reservar']) }}">
                📝 Reservar Salón
            </a>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <!-- Calendario de reservas -->
        <div class="tab-pane fade {{ $tab === 'uso' ? 'show active' : '' }}" id="uso" role="tabpanel">
            <div class="card p-3">
                <div id="calendar"></div>
            </div>
        </div>

        <!-- Formulario de reserva -->
        <div class="tab-pane fade {{ $tab === 'reservar' ? 'show active' : '' }}" id="reservar" role="tabpanel">
            <form id="formReserva" action="{{ route('gestor.salon.guardar') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nombre del Empleado</label>
                    <input type="text" class="form-control" name="empleado" required>
                </div>
                <div class="form-group mt-3">
                    <label>Fecha de la Reserva</label>
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
                    <textarea class="form-control" name="motivo" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Guardar Reservación</button>
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
        const reservas = @json($reservasCalendar);

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            events: reservas,
            eventClick: function(info) {
                const startDate = info.event.start;
                alert('Reserva:\n' +
                      'Empleado: ' + info.event.title + '\n' +
                      'Fecha: ' + startDate.toLocaleDateString() + '\n' +
                      'Hora: ' + startDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) + '\n' +
                      'Motivo: ' + info.event.extendedProps.motivo);
            }
        });
        calendar.render();

        // Validar fecha ocupada al enviar el formulario
        const formReserva = document.getElementById('formReserva');
        formReserva.addEventListener('submit', function(e) {
            const fecha = document.getElementById('fechaReserva').value;
            const horaInicio = document.getElementById('horaInicio').value.padStart(5, '0');
            const horaFin = document.getElementById('horaFin').value.padStart(5, '0');

            if (!fecha || !horaInicio || !horaFin) {
                return; // Si algo está vacío, no valida (HTML required lo hará)
            }

            // Validar si hay alguna reserva en esa fecha y horario
            const conflicto = reservas.some(evento => {
                const [eventDate, eventTimeStart] = evento.start.split('T');
                const eventTimeEnd = evento.end ? evento.end.split('T')[1].slice(0,5) : eventTimeStart;

                return eventDate === fecha &&
                       (
                           (horaInicio >= eventTimeStart && horaInicio < eventTimeEnd) ||
                           (horaFin > eventTimeStart && horaFin <= eventTimeEnd) ||
                           (horaInicio <= eventTimeStart && horaFin >= eventTimeEnd)
                       );
            });

            if(conflicto){
                e.preventDefault();
                alert('Este salón ya tiene una reserva en ese horario. Por favor elige otra fecha u horario.');
            }
        });
    });
</script>
@endsection

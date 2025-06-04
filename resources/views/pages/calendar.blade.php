@extends('adminlte::page')

@section('title', 'Calendario')

@section('content_header')
    <h1>Calendario</h1>
@stop

@section('content')
    {{-- Aquí pega el contenido HTML del calendario del demo de AdminLTE --}}
    <div class="row">
        <div class="col-md-3">
            <div id="external-events">
                <p class="text-center">
                    <strong>Eventos arrastrables</strong>
                </p>
                <div class="external-event bg-success">Almuerzo</div>
                <div class="external-event bg-warning">Ir a casa</div>
                <div class="external-event bg-info">Hacer la tarea</div>
                <div class="external-event bg-primary">Diseñar interfaz</div>
                <div class="external-event bg-danger">Dormir</div>
                <p>
                    <input type="checkbox" id="drop-remove" />
                    <label for="drop-remove">Quitar después de soltar</label>
                </p>
            </div>
        </div>
        <div class="col-md-9">
            <div id="calendar"></div>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
@stop

@section('js')
   @section('js')
    <!-- Scripts necesarios de FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/interaction.min.js"></script>

    <!-- Activar eventos arrastrables -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Hacer los eventos externos arrastrables
            var containerEl = document.getElementById('external-events');
            new FullCalendar.Draggable(containerEl, {
                itemSelector: '.external-event',
                eventData: function(eventEl) {
                    return {
                        title: eventEl.innerText.trim()
                    };
                }
            });

            // Inicializar el calendario
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                editable: true,
                droppable: true,
                events: [
                    {
                        title: 'Evento de todo el día',
                        start: '2025-05-01'
                    },
                    {
                        title: 'Evento largo',
                        start: '2025-05-23',
                        end: '2025-05-25'
                    }
                ]
            });

            calendar.render();
        });
    </script>
@endsection


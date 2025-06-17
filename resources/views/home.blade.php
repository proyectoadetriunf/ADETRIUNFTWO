@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    {{-- ✅ Título de bienvenida --}}
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-dark">Bienvenid@, {{ Auth::user()->name }}</h3>
        </div>
    </div>

    {{-- ✅ Tarjetas de estadísticas modernas --}}
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Proyectos Activos</h6>
                    <h4 class="font-weight-bold">{{ $totalProyectos }}</h4>
                    <i class="fas fa-folder fa-lg text-primary float-right"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Beneficiarios este año</h6>
                    <h4 class="font-weight-bold">{{ $totalBeneficiarios }}</h4>
                    <i class="fas fa-user-friends fa-lg text-success float-right"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Donaciones Recibidas</h6>
                    <h4 class="font-weight-bold">{{ $totalDonaciones }}</h4>
                    <i class="fas fa-dollar-sign fa-lg text-warning float-right"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Solicitudes Pendientes</h6>
                    <h4 class="font-weight-bold">{{ $totalSolicitudesPendientes }}</h4>
                    <i class="fas fa-exclamation-circle fa-lg text-danger float-right"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Tabla de proyectos recientes --}}
    <div class="mt-4">
        <h4>Proyectos Recientes</h4>
        <x-adminlte-datatable id="proyectos" :heads="['Proyecto', 'Estado', 'Año']" striped hoverable>
            @foreach ($proyectos as $proy)
                <tr>
                    <td>{{ $proy->nombre }}</td>
                    <td>{{ $proy->estado }}</td>
                    <td>{{ $proy->anio }}</td>
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </div>
{{-- ✅ Mapa + Chat en fila --}}
<div class="row mt-5">
    <div class="col-md-6">
        <h4>📍 Distribución de proyectos por ubicación</h4>
        <div id="mapaBase" style="height: 300px; border-radius: 10px;"></div>
    </div>

    <div class="col-md-6">
        <h4>💬 Chat entre usuarios</h4>
        <div class="card">
            <div class="card-header bg-primary text-white">Conversación</div>
            <div class="card-body" id="chat-box" style="height: 250px; overflow-y: scroll;"></div>
            <div class="card-footer p-2">
                <form id="chat-form">
                    <div class="input-group">
                        <input type="text" id="mensaje" class="form-control" placeholder="Escribe un mensaje..." required>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Enviar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endsection

@section('js')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    {{-- Mapa --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var map = L.map('mapaBase').setView([14.6349, -86.25], 7);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const proyectos = @json($proyectos);

            proyectos.forEach(p => {
                if (p.lat && p.lng && !isNaN(p.lat) && !isNaN(p.lng)) {
                    L.circleMarker([p.lat, p.lng], {
                        radius: 6,
                        color: 'blue',
                        fillColor: 'blue',
                        fillOpacity: 0.7
                    }).addTo(map)
                      .bindPopup(`<strong>${p.nombre}</strong><br>${p.departamento}`);
                }
            });
        });
    </script>

    {{-- Chat --}}
    <script>
        function cargarMensajes() {
            $.get('/chat', function(data) {
                $('#chat-box').html('');
                data.reverse().forEach(function(msg) {
                    const eliminar = msg.usuario_id === {{ auth()->id() }} ? `
                        <button class="btn btn-sm btn-link text-danger" onclick="eliminarMensaje('${msg._id.$oid}')">Eliminar</button>
                    ` : '';
                    $('#chat-box').append(`
                        <div class="mb-2">
                            <strong>${msg.nombre}</strong>: ${msg.contenido} ${eliminar}
                        </div>
                    `);
                });
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            });
        }

        function eliminarMensaje(id) {
            $.ajax({
                url: '/chat/mensaje/' + id,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: cargarMensajes
            });
        }

        $('#chat-form').on('submit', function(e) {
            e.preventDefault();
            $.post('/chat/mensaje', {
                _token: '{{ csrf_token() }}',
                mensaje: $('#mensaje').val()
            }, function() {
                $('#mensaje').val('');
                cargarMensajes();
            });
        });

        cargarMensajes();
        setInterval(cargarMensajes, 5000);
    </script>
@endsection

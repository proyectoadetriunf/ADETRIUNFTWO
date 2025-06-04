@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Bienvenido, Coordinador {{ Auth::user()->name }}</h1>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-adminlte-info-box title="Total Proyectos Activos" text="{{ $totalProyectos }}" icon="fas fa-folder" theme="primary"/>
    </div>
    <div class="col-md-3">
        <x-adminlte-info-box title="Beneficiarios este año" text="{{ $totalBeneficiarios }}" icon="fas fa-user-friends" theme="success"/>
    </div>
    <div class="col-md-3">
        <x-adminlte-info-box title="Donaciones Recibidas" text="{{ $totalDonaciones }}" icon="fas fa-dollar-sign" theme="warning"/>
    </div>
    <div class="col-md-3">
        <x-adminlte-info-box title="Solicitudes Pendientes" text="{{ $totalSolicitudesPendientes }}" icon="fas fa-exclamation-circle" theme="danger"/>
    </div>
</div>

<div class="mt-4">
    <h4>Proyectos Recientes</h4>
    <x-adminlte-datatable id="tablaProyectos" :heads="['Proyecto', 'Estado', 'Año']" striped hoverable>
        @foreach ($proyectos as $proy)
            <tr>
                <td>{{ $proy->nombre_proyecto }}</td>
                <td>{{ $proy->estado_proyecto }}</td>
                <td>{{ $proy->anio_proyecto }}</td>
            </tr>
        @endforeach
    </x-adminlte-datatable>
</div>

<div class="mt-5">
    <h4>📍 Distribución de proyectos por departamento</h4>
    <div id="mapaBase" style="height: 400px;"></div>
</div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/plugins/jqvmap/jqvmap.min.css">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/plugins/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/plugins/jqvmap/maps/jquery.vmap.world.js"></script>

    <script>
        console.log("🗺️ Ejecutando script de mapa...");

        $(function () {
            if ($('#mapaBase').length) {
                $('#mapaBase').vectorMap({
                    map: 'world_en',
                    backgroundColor: 'transparent',
                    regionStyle: {
                        initial: {
                            fill: '#007bff'
                        }
                    }
                });
            } else {
                console.warn("El div #mapaBase no se encontró en el DOM.");
            }
        });
    </script>
@endsection


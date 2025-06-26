@extends('adminlte::page')

@section('title', '📊 Resumen General')

@section('content')
@extends('adminlte::page')

@section('title', '📊 Resumen General del Sistema')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">📊 Resumen General del Sistema</h1>

    {{-- Fila superior (3 tarjetas) --}}
    <div class="row text-white">
        <div class="col-md-4 mb-3">
            <a href="{{ url('gestor/proyectos?tab=ver') }}" class="text-white text-decoration-none">
                <div class="card bg-primary shadow">
                    <div class="card-body text-center">
                        <h5>Total de Proyectos</h5>
                        <h2>{{ $totalProyectos }}</h2>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ url('gestor/beneficiarios') }}" class="text-white text-decoration-none">
                <div class="card bg-info shadow">
                    <div class="card-body text-center">
                        <h5>Beneficiarios</h5>
                        <h2>{{ $totalBeneficiarios }}</h2>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ url('gestor/citas') }}" class="text-white text-decoration-none">
                <div class="card bg-secondary shadow">
                    <div class="card-body text-center">
                        <h5>Citas Programadas</h5>
                        <h2>{{ $totalCitas }}</h2>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Fila inferior (2 tarjetas) --}}
    <div class="row text-white">
        <div class="col-md-6 mb-3">
            <div class="card bg-success shadow">
                <div class="card-body text-center">
                    <h5>Finalizados</h5>
                    <h2>{{ $finalizados }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card bg-warning shadow">
                <div class="card-body text-center text-dark">
                    <h5>En Progreso</h5>
                    <h2>{{ $enProgreso }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráfico de avance promedio --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <h5>📈 Avance Promedio de Proyectos</h5>
                    <p class="h3">{{ number_format($avanceTotal, 2) }}%</p>
                    <canvas id="graficoAvance" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoAvance');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($datosGrafica->pluck('nombre')) !!},
            datasets: [{
                label: '% Avance',
                data: {!! json_encode($datosGrafica->pluck('avance')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
@endpush

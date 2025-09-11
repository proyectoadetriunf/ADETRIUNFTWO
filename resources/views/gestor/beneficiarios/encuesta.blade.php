@extends('adminlte::page')

@section('title', 'Encuesta al Beneficiario')

@section('content')
<div class="container mt-4">
    <h3>📝 Encuesta - {{ $persona['nombres'] ?? 'Beneficiario' }}</h3>

    <form action="{{ route('beneficiarios.guardarEncuesta', $beneficiario['_id']) }}" method="POST">
        @csrf

        <div id="preguntas">
            <div class="form-group mb-3">
                <label>Pregunta</label>
                <input type="text" name="pregunta[]" class="form-control" placeholder="Escribe una pregunta">
            </div>
            <div class="form-group mb-3">
                <label>Respuesta</label>
                <input type="text" name="respuesta[]" class="form-control" placeholder="Escribe la respuesta">
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="agregarCampo()">➕ Añadir otra</button>

        <button type="submit" class="btn btn-primary">💾 Guardar Encuesta</button>
    </form>
</div>

<script>
    function agregarCampo() {
        const container = document.getElementById('preguntas');
        const grupo = `
            <div class="form-group mb-3">
                <label>Pregunta</label>
                <input type="text" name="pregunta[]" class="form-control" placeholder="Escribe una pregunta">
            </div>
            <div class="form-group mb-3">
                <label>Respuesta</label>
                <input type="text" name="respuesta[]" class="form-control" placeholder="Escribe la respuesta">
            </div>
        `;
        container.insertAdjacentHTML('beforeend', grupo);
    }
</script>
@endsection

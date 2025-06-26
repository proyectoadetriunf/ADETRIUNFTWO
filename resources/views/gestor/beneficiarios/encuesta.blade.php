@extends('adminlte::page')

@section('title', 'Encuesta del Beneficiario')

@section('content')
<div class="container mt-4">
    <h1>📝 Encuesta Personalizada</h1>

    <form action="{{ route('beneficiarios.guardarEncuesta', $beneficiario['_id']) }}" method="POST">
        @csrf

        <div id="preguntas-container">
            <div class="pregunta-item mb-3 border p-3 rounded">
                <label>Pregunta:</label>
                <input type="text" name="preguntas[0][pregunta]" class="form-control" required>

                <label class="mt-2">Respuesta:</label>
                <input type="text" name="preguntas[0][respuesta]" class="form-control" required>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="agregarPregunta()">➕ Agregar Pregunta</button>
        <br>
        <button type="submit" class="btn btn-primary">💾 Guardar Encuesta</button>
    </form>
</div>

<script>
    let index = 1;

    function agregarPregunta() {
        const container = document.getElementById('preguntas-container');
        const div = document.createElement('div');
        div.className = 'pregunta-item mb-3 border p-3 rounded';
        div.innerHTML = `
            <label>Pregunta:</label>
            <input type="text" name="preguntas[${index}][pregunta]" class="form-control" required>

            <label class="mt-2">Respuesta:</label>
            <input type="text" name="preguntas[${index}][respuesta]" class="form-control" required>
        `;
        container.appendChild(div);
        index++;
    }
</script>
@endsection

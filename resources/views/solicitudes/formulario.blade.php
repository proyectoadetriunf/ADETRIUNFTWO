<form method="POST" action="{{ route('solicitudes.store') }}">
    @csrf
    <div class="form-group">
        <label for="asunto">Asunto de la solicitud</label>
        <input type="text" name="asunto" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>

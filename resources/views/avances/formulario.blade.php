<form method="POST" action="{{ route('avances.store') }}">
    @csrf
    <div class="form-group">
        <label for="descripcion">Descripción del avance</label>
        <textarea name="descripcion" class="form-control" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>

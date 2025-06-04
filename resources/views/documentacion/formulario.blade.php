<form method="POST" action="{{ route('documentacion.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="archivo">Subir archivo</label>
        <input type="file" name="archivo" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>

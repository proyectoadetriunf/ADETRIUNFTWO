@extends('adminlte::page')

@section('title', 'Mi Perfil')

@section('content')
<div class="container" style="max-width: 700px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">👤 Mi Perfil</h5>
        </div>
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="text-center mb-3">
                    @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" class="rounded-circle" width="120">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" class="rounded-circle" width="120">
                    @endif
                </div>

                <div class="form-group">
                    <label for="foto">Foto de perfil</label>
                    <input type="file" name="foto" class="form-control-file">
                </div>

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Rol</label>
                    <input type="text" class="form-control" value="{{ $user->rol->nombre ?? 'No asignado' }}" disabled>
                </div>

                <div class="form-group">
                    <label>Código de Usuario</label>
                    <input type="text" class="form-control" value="{{ $user->id }}" disabled>
                </div>

                <button class="btn btn-primary btn-block">Guardar cambios</button>
            </form>
        </div>
    </div>
</div>
@endsection

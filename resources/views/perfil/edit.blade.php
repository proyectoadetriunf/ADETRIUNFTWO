@extends('adminlte::page')

@section('title', 'Perfil')

@section('content_header')
    <h3>Perfil</h3>
@stop

@section('content')
<div class="row">
    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                <strong>Ups... hubo algunos errores:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li style="list-style: disc; margin-left: 20px;">{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Columna izquierda -->
    <div class="col-md-8">
        <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="perfil-card">
                <div class="perfil-header">Información personal</div>

                <div class="form-group mt-3">
                    <label><strong>Nombre</strong></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label><strong>Correo electrónico</strong></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <hr>
                <strong class="text-muted">CAMBIAR CONTRASEÑA</strong>

                <div class="form-group mt-2">
                    <label>Contraseña antigua</label>
                    <input type="password" name="old_password" class="form-control" placeholder="********">
                </div>

                <div class="form-group">
                    <label>Nueva contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="Nueva contraseña">
                </div>

                <div class="form-group">
                    <label>Confirmar nueva contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar nueva contraseña">
                </div>

                <div class="form-check mt-2">
                    <input type="checkbox" class="form-check-input" name="is_active" {{ $user->is_active ? 'checked' : '' }}>
                    <label class="form-check-label">Activo</label>
                </div>

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Actualizar datos
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Columna derecha -->
    <div class="col-md-4">
        <div class="perfil-card text-center">
            <h5 class="mb-3"><strong>Foto de perfil</strong></h5>
            <img id="avatarPreview"
                 src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('img/avatar.png') }}"
                 class="custom-avatar shadow-sm mb-3"
                 alt="Foto de perfil">
            <input type="file" name="photo" class="form-control" onchange="previewAvatar(this)">
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .perfil-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        padding: 20px;
        margin-bottom: 20px;
    }
    .perfil-header {
        background-color: rgba(54, 162, 235, 1);
        color: white;
        border-radius: 5px;
        padding: 10px;
        font-weight: bold;
        text-align: center;
    }
    .custom-avatar {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 1px solid #ccc;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
</style>
@stop

@section('js')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@stop

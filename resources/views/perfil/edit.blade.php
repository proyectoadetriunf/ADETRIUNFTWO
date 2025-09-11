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

                <div class="form-group mt-3">
                    <label><strong>Foto de perfil</strong></label>
                    <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewAvatar(this)">
                    <small class="text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
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
            <div class="avatar-container">
                <img id="avatarPreview"
                     src="{{ $user->avatar_url }}"
                     class="custom-avatar shadow-sm mb-3"
                     alt="Foto de perfil">
                
                @if($user->photo)
                    <div class="avatar-overlay">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removePhoto()" title="Eliminar foto">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @endif
            </div>
            
            @if($user->photo)
                <p class="text-muted small">Tienes una foto de perfil personalizada</p>
            @else
                <p class="text-muted small">Usando avatar por defecto</p>
            @endif
        
            @if(session('error_photo'))
                <div class="alert alert-danger mt-2">{{ session('error_photo') }}</div>
            @endif
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
        transition: all 0.3s ease;
    }
    .avatar-container {
        position: relative;
        display: inline-block;
    }
    .avatar-overlay {
        position: absolute;
        top: 5px;
        right: 5px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .avatar-container:hover .avatar-overlay {
        opacity: 1;
    }
    .avatar-container:hover .custom-avatar {
        filter: brightness(0.8);
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

    function removePhoto() {
        if (confirm('¿Estás seguro de que quieres eliminar tu foto de perfil?')) {
            fetch('{{ route("perfil.remove.photo") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cambiar la imagen al avatar por defecto
                    document.getElementById('avatarPreview').src = '{{ asset("img/avatar.png") }}';
                    
                    // Recargar la página para actualizar la interfaz
                    location.reload();
                } else {
                    alert('Error al eliminar la foto');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar la foto');
            });
        }
    }
</script>
@stop

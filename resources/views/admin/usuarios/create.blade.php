@extends('adminlte::page')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="card shadow-sm border-0 rounded-4 my-4">
        <div class="card-header bg-success text-white rounded-top-4">
            <h2 class="mb-0">Crear Nuevo Usuario</h2>
        </div>
        <div class="card-body">

            {{-- Errores de validación --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>⚠️ Error:</strong> Revisa los campos.
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('usuarios.store') }}" method="POST" novalidate>
                @csrf

                <!-- Nombre -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nombre</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Ingresa el nombre completo"
                        required
                        pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$"
                        title="El nombre solo debe contener letras y espacios.">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Correo -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="ejemplo@dominio.com"
                        required
                        pattern="^[^@]+@[^@]+\.(com)$"
                        title="Debe ser un correo válido que termine en .com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Mínimo 6 caracteres"
                        required
                        minlength="6"
                        title="La contraseña debe tener al menos 6 caracteres.">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirmar contraseña -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirmar Contraseña</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="form-control"
                        placeholder="Confirma la contraseña"
                        required
                        minlength="6"
                        title="Debe coincidir con la contraseña ingresada.">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary px-4">↩ Cancelar</a>
                    <button type="submit" class="btn btn-success px-4">Crear Usuario</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection


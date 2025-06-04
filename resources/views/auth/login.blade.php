<!-- resources/views/auth/login.blade.php -->

@extends('layouts.app')

@section('content')
<style>
    body {
        background: url('/images/fondo_institucional.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', sans-serif;
    }
    .login-container {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(6px);
    }
    .login-card {
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.2);
        width: 380px;
        animation: fadeIn 1s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="login-container">
    <div class="login-card">
        <h3 class="text-center mb-4 text-primary">Bienvenido a ADETRIUNF</h3>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input id="email" type="email" class="form-control" name="email" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>

            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Recuérdame</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>

            <div class="mt-3 text-center">
                <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </div>
</div>
@endsection

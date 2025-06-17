@extends('layouts.app')

@section('content')
<style>
    body {
        margin: 0;
        min-height: 100vh;
        background: linear-gradient(to bottom, #f39c12, #e74c3c, #e84393);
        font-family: 'Segoe UI', sans-serif;
    }

    .topbar {
        background-color: white;
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .topbar .brand {
        font-weight: bold;
        font-size: 18px;
    }

    .topbar .links a {
        margin-left: 15px;
        color: #333;
        text-decoration: none;
        font-size: 14px;
    }

    .login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 70px); /* Resta la altura de la barra superior */
        padding-top: 30px;
    }

    .login-card {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 400px;
    }

    .login-card h3 {
        font-weight: bold;
        color: #2c3e50;
        text-align: center;
        margin-bottom: 25px;
    }

    .form-control {
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .btn-primary {
        width: 100%;
        border-radius: 8px;
        font-weight: bold;
    }

    .form-check-label {
        font-size: 14px;
    }

    .text-link {
        display: block;
        margin-top: 10px;
        text-align: center;
        font-size: 14px;
    }
</style>



<!--  Login Form -->
<div class="login-wrapper">
    <div class="login-card">
        <h3>Bienvenido a <span class="text-primary">ADETRIUNF</span></h3>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" class="form-control" placeholder="Ingresa tu correo" required>

            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" class="form-control" placeholder="Ingresa tu contraseña" required>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember">Recuérdame</label>
            </div>

            <button type="submit" class="btn btn-primary">Iniciar sesión</button>

            <a class="text-link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
        </form>
    </div>
</div>
@endsection


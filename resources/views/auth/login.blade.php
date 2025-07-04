@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

<div class="d-flex align-items-center justify-content-center min-vh-100">
    <header class="main-header">
        <div class="main-cont">
            <div class="desc-header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo School" class="school-image">
            </div>
        </div>

        <div class="cont-header">
            <h1>Bienvenid@s</h1>

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Administrador</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Gestor Proyecto</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!-- Administrador -->
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <input type="hidden" name="login_role" value="admin">

                        <label for="email_admin">Usuario</label>
                        <input type="email" name="email" id="email_admin" placeholder="Correo electrónico" required>

                        <label for="password_admin">Contraseña</label>
                        <input type="password" name="password" id="password_admin" placeholder="Contraseña" required>

                        @if ($errors->has('error'))
                            <div class="alert alert-danger mt-2">
                                {{ $errors->first('error') }}
                            </div>
                        @elseif ($errors->any())
                            <div class="alert alert-danger mt-2">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <button id="Loginusuario" type="submit">Iniciar sesión</button>

                        <a href="{{ route('register') }}" class="btn btn-link d-block text-center mt-2">
                            ¿No tienes cuenta? Regístrate
                        </a>
                    </form>
                </div>

                <!-- Gestor Proyecto -->
                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <input type="hidden" name="login_role" value="moderador">

                        <label for="email_gestor">Usuario</label>
                        <input type="email" name="email" id="email_gestor" placeholder="Correo electrónico" required>

                        <label for="password_gestor">Contraseña</label>
                        <input type="password" name="password" id="password_gestor" placeholder="Contraseña" required>

                        @if ($errors->has('error'))
                            <div class="alert alert-danger mt-2">
                                {{ $errors->first('error') }}
                            </div>
                        @elseif ($errors->any())
                            <div class="alert alert-danger mt-2">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <button id="Loginjefedepto" type="submit">Iniciar sesión</button>

                        <a href="{{ route('register') }}" class="btn btn-link d-block text-center mt-2">
                            ¿No tienes cuenta? Regístrate
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </header>
</div>

<script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>
<script src="{{ asset('js/login.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@if(session('rol_actual'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const role = "{{ session('rol_actual') }}";
        if (role === 'admin') {
            document.querySelector('#home-tab').click();
        } else if (role === 'moderador') {
            document.querySelector('#profile-tab').click();
        }
    });
</script>
@endif

@endsection

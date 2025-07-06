<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> ADETRIUNF | Recuperar contraseña</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    {{-- Bootstrap y CSS personalizado --}}
    <link href="{{ asset('login_libs/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('login_libs/login.css') }}" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <div id="formContent">

            <!-- Título -->
            <div>
                <h4><b>ADETRIUNF</b></h4>
            </div>

            <!-- Icono -->
            <div>
                <img src="{{ asset('login_libs/email.png') }}" id="icon" alt="User Icon" />
            </div>

            <!-- Mensaje de éxito -->
            @if (session('status'))
                <div class="alert alert-success mx-4 mt-2" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Formulario -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <input type="email" id="email" name="email" placeholder="Correo Electrónico"
                    value="{{ old('email') }}" required autocomplete="email" autofocus
                    class="@error('email') is-invalid @enderror">

                @error('email')
                    <div style="color:red; font-size: 0.9rem; margin-bottom: 10px;">
                        {{ $message }}
                    </div>
                @enderror

                <div class="loginButton">
                    <input type="submit" value="Enviar Contraseña">
                </div>
            </form>

            <!-- Enlace volver -->
            <div id="formFooter">
                <a class="underlineHover" href="{{ route('login') }}">Volver a iniciar sesión</a>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('login_libs/jquery.min.js') }}"></script>
    <script src="{{ asset('login_libs/bootstrap.min.js') }}"></script>
</body>
</html>

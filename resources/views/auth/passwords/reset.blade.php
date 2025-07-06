<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ADETRIUNF | Restablecer Contraseña</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

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
                <img src="{{ asset('login_libs/lock.png') }}" id="icon" alt="Lock Icon" />
            </div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('password.update') }}" onsubmit="return compararPasswords();">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <input type="email" id="email" name="email" placeholder="Correo Electrónico"
                    value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                    class="@error('email') is-invalid @enderror">

                @error('email')
                    <div style="color:red; font-size: 0.9rem; margin-bottom: 10px;">
                        {{ $message }}
                    </div>
                @enderror

                <input type="password" id="password" name="password" placeholder="Nueva Contraseña" required
                    class="@error('password') is-invalid @enderror">
                @error('password')
                    <div style="color:red; font-size: 0.9rem; margin-bottom: 10px;">
                        {{ $message }}
                    </div>
                @enderror

                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirmar Contraseña" required>

                <div class="loginButton">
                    <input type="submit" value="Cambiar Contraseña">
                </div>
            </form>

            <!-- Enlace volver -->
            <div id="formFooter">
                <a class="underlineHover" href="{{ route('login') }}">Volver a iniciar sesión</a>
            </div>

        </div>
    </div>

    <script>
        function compararPasswords() {
            var pass1 = document.getElementById('password').value;
            var pass2 = document.getElementById('password_confirmation').value;

            if (pass1 !== pass2) {
                alert('Las contraseñas no coinciden.');
                return false;
            }
            return true;
        }
    </script>

    <script src="{{ asset('login_libs/jquery.min.js') }}"></script>
    <script src="{{ asset('login_libs/bootstrap.min.js') }}"></script>
</body>

</html>

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Ruta de redirección tras inicio de sesión exitoso
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $loginRole = $request->input('login_role'); // admin o moderador

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Validar que el rol del usuario coincida con la pestaña usada
            if (
                ($loginRole === 'admin' && $user->rol_id !== 'admin') ||
                ($loginRole === 'moderador' && $user->rol_id !== 'moderador')
            ) {
                Auth::logout(); // cerrar sesión

                return redirect()->back()
                    ->withErrors(['error' => 'Lugar incorrecto. Te hemos redirigido a tu pestaña.'])
                    ->withInput()
                    ->with('error_rol', '')
                    ->with('rol_actual', $user->rol_id); // Para cambiar pestaña desde el JS
            }

            // Redireccionar a la ruta correspondiente si todo está bien
            return redirect()->intended($this->redirectTo);
        }

        // Si las credenciales son incorrectas
        return redirect()->back()
            ->withErrors(['error' => 'Credenciales incorrectas.'])
            ->withInput();
    }
}

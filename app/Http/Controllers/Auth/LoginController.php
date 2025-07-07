<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /** Ruta de redirección tras un login exitoso */
    protected $redirectTo = '/home';

    public function __construct()
    {
        // Invitados pueden acceder a login; autenticados solo a logout
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Sobrescribe el método de autenticación para añadir lógica extra.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $loginRole   = $request->input('login_role'); // 'admin' o 'moderador'

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            /* 1️  Cuenta desactivada */
            if (!$user->is_active) {
                Auth::logout();
                return back()
                    ->withErrors(['error' => 'Tu cuenta está desactivada.'])
                    ->withInput();
            }

            /* 2️  Verifica que la pestaña (rol) coincida con el rol real */
            $rolOk = !(
                ($loginRole === 'admin'      && $user->rol_id !== 'admin') ||
                ($loginRole === 'moderador'  && $user->rol_id !== 'moderador')
            );

            if (!$rolOk) {
                Auth::logout();
                return back()
                    ->withErrors(['error' => 'Lugar incorrecto. Te hemos redirigido a tu pestaña.'])
                    ->withInput()
                    ->with('error_rol', '')
                    ->with('rol_actual', $user->rol_id); // para que JS cambie de tab
            }

            /* 3️  Actualiza última conexión + marca carga completa */
            $user->last_login_at = now();   // campo datetime en MongoDB
            $user->is_loading    = false;   // opcional: marca que ya terminó carga
            $user->save();

            /* 4️  Redirecciona a destino */
            return redirect()->intended($this->redirectTo);
        }

        /* 5️ Credenciales inválidas */
        return back()
            ->withErrors(['error' => 'Credenciales incorrectas.'])
            ->withInput();
    }
}

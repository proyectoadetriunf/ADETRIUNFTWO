<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Persona;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Buscar usuario y validar contraseña
        $user = Persona::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // 2. Verificar estado y rol
        if (!$user->is_active) {
            return response()->json(['message' => 'Tu cuenta está desactivada'], 403);
        }

        if (!in_array($user->rol_id, ['admin', 'moderador'])) {
            return response()->json(['message' => 'Acceso denegado para este rol'], 403);
        }

        // 3. Generar JWT
        $token = JWTAuth::fromUser($user);

        // 4. Anotar última conexión y loading
        $user->last_login = now();
        $user->is_loading = false;
        $user->save();

        // 5. Respuesta
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'user' => [
                'nombre' => $user->nomb_per ?? '',
                'email'  => $user->email,
                'rol_id' => $user->rol_id,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Sesión cerrada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo cerrar sesión.'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Persona;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = Persona::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Solo permitir admin, coordinador y técnico
        if (!in_array($user->rol_id, [1, 2, 3])) {
            return response()->json(['message' => 'Acceso denegado para este rol'], 403);
        }

        // Opcional: eliminar tokens anteriores para evitar duplicados
        $user->tokens()->delete();

        $token = $user->createToken('web-token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'nombre' => $user->nomb_per ?? '',
                'email' => $user->email,
                'rol_id' => $user->rol_id
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }
}

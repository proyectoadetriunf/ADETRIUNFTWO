<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        // Usar la colección correcta: users
        $user = DB::connection('mongodb')->collection('users')->where('email', $request->email)->first();

        // Validar existencia y contraseña
        if (! $user || ! Hash::check($request->password, $user['password'])) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        // Convertir a modelo User para JWT
        $userModel = new \App\Models\User((array) $user);
        $userModel->_id = $user['_id'];

        $token = JWTAuth::fromUser($userModel);

        return response()->json([
            'token' => $token,
            'usuario' => [
                'name' => $user['name'],
                'email' => $user['email'],
                'rol_id' => $user['rol_id']
            ]
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Sesión cerrada']);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use MongoDB\BSON\ObjectId;

class RolController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        $roles = Rol::all(); // Asegúrate de que esta colección tenga los roles tipo texto
        return view('admin.roles.index', compact('usuarios', 'roles'));
    }

    public function asignar(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'rol_id' => 'required|string|in:admin,moderador,usuario'
        ]);

        try {
            $objectId = new ObjectId($request->user_id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ID de usuario inválido');
        }

        $usuario = User::where('_id', $objectId)->first();

        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }

        $usuario->rol_id = $request->rol_id;
        $usuario->save();

        return redirect()->route('admin.roles')->with('success', 'Rol asignado correctamente.');
    }
}


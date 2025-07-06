<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
<<<<<<< HEAD
use MongoDB\BSON\ObjectId;
=======
use App\Models\Rol; // Asegúrate de tener este modelo apuntando a persona_roles
>>>>>>> a42c1154a0cfe32c61ad927fa3ec200f11f22a3e

class RolController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        $roles = Rol::all(); // Cargamos los roles desde la colección persona_roles
        return view('admin.roles.index', compact('usuarios', 'roles'));
    }

    public function asignar(Request $request)
    {
        $request->validate([
<<<<<<< HEAD
            'user_id' => 'required|string', // No uses exists porque es MongoDB
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
=======
            'user_id' => 'required|exists:users,_id',
            'rol_id' => 'required|integer',
        ]);

        $usuario = User::findOrFail($request->user_id);
        $usuario->rol_id = (int) $request->rol_id;
>>>>>>> a42c1154a0cfe32c61ad927fa3ec200f11f22a3e
        $usuario->save();

        return redirect()->route('admin.roles')->with('success', 'Rol asignado correctamente.');
    }
}








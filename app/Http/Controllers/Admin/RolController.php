<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol; // Asegúrate de tener este modelo apuntando a persona_roles

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
            'user_id' => 'required|exists:users,_id',
            'rol_id' => 'required|integer',
        ]);

        $usuario = User::findOrFail($request->user_id);
        $usuario->rol_id = (int) $request->rol_id;
        $usuario->save();

        return redirect()->route('admin.roles')->with('success', 'Rol asignado correctamente.');
    }
}








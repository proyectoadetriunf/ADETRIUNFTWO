<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RolController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('admin.roles.index', compact('usuarios'));
    }

    public function asignar(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,_id',
            'rol_id' => 'required|string|in:admin,moderador,usuario'
        ]);

        $usuario = User::find($request->user_id);
        $usuario->rol_id = $request->rol_id;
        $usuario->save();

        return redirect()->route('admin.roles')->with('success', 'Rol asignado correctamente');
    }
}

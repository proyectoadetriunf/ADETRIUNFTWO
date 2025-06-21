<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsuarioController extends Controller
{
    public function index()
    {
        // Aquí luego podrás cargar los usuarios desde la base de datos
        //return view('admin.usuarios.index');
        $usuarios = User::all(); // Obtenemos todos los usuarios de la base de datos
        return view('admin.usuarios.index', compact('usuarios'));
    }
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
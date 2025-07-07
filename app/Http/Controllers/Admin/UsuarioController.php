<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use App\Models\User;

class UsuarioController extends Controller
{
    /* === LISTAR === */
    public function index()
    {
        $usuarios = User::all();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /* === FORMULARIO CREAR === */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /* === GUARDAR === */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => ['required','string','max:255','regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
            'email'      => ['required','string','email','regex:/^[^@]+@[^@]+\.(com)$/i','max:255','unique:users'],
            'password'   => ['required','string','min:6','confirmed'],
            'is_active'  => ['required','boolean'],
            'is_loading' => ['nullable','boolean'],
        ]);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => bcrypt($request->password),
            'is_active'  => $request->boolean('is_active'),
            'is_loading' => $request->boolean('is_loading'),
        ]);

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario creado correctamente.');
    }

    /* === FORMULARIO EDITAR === */
    public function edit($id)
    {
        $usuario = User::where('_id', new ObjectId($id))->firstOrFail();
        return view('admin.usuarios.edit', compact('usuario'));
    }

    /* === ACTUALIZAR === */
    public function update(Request $request, $id)
    {
        $usuario = User::where('_id', new ObjectId($id))->firstOrFail();

        $request->validate([
            'name'       => ['required','string','max:255','regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'],
            'email'      => ['required','string','email','regex:/^[^@]+@[^@]+\.(com)$/i','max:255',
                             'unique:users,email,'.$usuario->_id.',_id'],
            'password'   => ['nullable','string','min:6','confirmed'],
            'is_active'  => ['required','boolean'],
            'is_loading' => ['nullable','boolean'],
        ]);

        $usuario->name       = $request->name;
        $usuario->email      = $request->email;
        $usuario->is_active  = $request->boolean('is_active');
        $usuario->is_loading = $request->boolean('is_loading');

        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->password);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario actualizado correctamente.');
    }

    /* === ELIMINAR === */
    public function destroy($id)
    {
        try {
            $deleted = User::where('_id', new ObjectId($id))->delete();

            return redirect()->route('usuarios.index')
                   ->with($deleted ? 'success' : 'error',
                          $deleted ? 'Usuario eliminado correctamente.'
                                   : 'No se pudo eliminar el usuario.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')
                   ->with('error', 'Error al eliminar usuario: '.$e->getMessage());
        }
    }
}

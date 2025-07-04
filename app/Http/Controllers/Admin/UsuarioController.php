<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use MongoDB\BSON\ObjectId;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
{
    $rules = [
        'name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'
        ],
        'email' => [
            'required',
            'string',
            'email',
            'regex:/^[^@]+@[^@]+\.(com)$/i',
            'max:255',
            'unique:users',
        ],
        'password' => [
            'required',
            'string',
            'min:6',
            'confirmed',
        ],
    ];

    $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.regex' => 'El nombre solo puede contener letras y espacios.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'Debe ingresar un correo válido.',
        'email.regex' => 'El correo debe contener @ y terminar en .com.',
        'email.unique' => 'Este correo ya está en uso.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        'password.confirmed' => 'La confirmación de contraseña no coincide.',
    ];

    $validated = $request->validate($rules, $messages);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);

    return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
}

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'regex:/^[^@]+@[^@]+\.(com)$/i',
                'max:255',
                'unique:users,email,' . $usuario->_id . ',_id',
            ],
            'password' => 'nullable|string|min:6|confirmed',
        ];

        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo debe tener un formato válido.',
            'email.regex' => 'El correo debe contener @ y terminar en .com.',
            'email.unique' => 'Este correo ya está en uso.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ];

        $validated = $request->validate($rules, $messages);

        $usuario->name = $validated['name'];
        $usuario->email = $validated['email'];

        if (!empty($validated['password'])) {
            $usuario->password = bcrypt($validated['password']);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        try {
            $objectId = new ObjectId($id);

            $deleted = User::where('_id', $objectId)->delete();

            if ($deleted) {
                return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
            } else {
                return redirect()->route('usuarios.index')->with('error', 'No se pudo eliminar el usuario.');
            }

        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Error al eliminar usuario: ' . $e->getMessage());
        }
    }
}




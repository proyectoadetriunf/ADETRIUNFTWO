<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('perfil.edit', compact('user'));
    }

    public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->_id . ',_id',
        'password' => 'nullable|string|min:6|confirmed',
        'photo' => 'nullable|image|max:4096',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->is_active = $request->has('is_active');

    // Cambiar contraseña
    if ($request->filled('old_password') && Hash::check($request->old_password, $user->password)) {
        $user->password = Hash::make($request->password);
    }

    // ✅ Subir y guardar imagen
    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $folder = 'perfiles';

        $file->storeAs('public/' . $folder, $filename);

        // Borrar anterior si existe
        if ($user->photo && Storage::exists('public/' . $user->photo)) {
            Storage::delete('public/' . $user->photo);
        }

        $user->photo = $folder . '/' . $filename;
    }

    $user->save();

    return back()->with('success', 'Perfil actualizado correctamente');
}
}
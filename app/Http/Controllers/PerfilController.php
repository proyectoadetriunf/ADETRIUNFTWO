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
            'old_password' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_active = $request->has('is_active');

        // Cambiar contraseña
        if ($request->filled('password')) {
            if (!$request->filled('old_password')) {
                return back()->withErrors(['old_password' => 'Debes ingresar tu contraseña actual para cambiarla']);
            }
            
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'La contraseña actual es incorrecta']);
            }
            
            $user->password = Hash::make($request->password);
        }

        // Eliminar foto de perfil si se solicita
        if ($request->has('remove_photo')) {
            if ($user->photo && Storage::exists('public/' . $user->photo)) {
                Storage::delete('public/' . $user->photo);
            }
            $user->photo = null;
        }
        // Subir nueva foto de perfil
        elseif ($request->hasFile('photo')) {
            try {
                $file = $request->file('photo');
                $filename = 'perfil_' . $user->_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $folder = 'perfiles';

                // Guardar nueva imagen
                $file->storeAs('public/' . $folder, $filename);

                // Borrar foto anterior si existe
                if ($user->photo && Storage::exists('public/' . $user->photo)) {
                    Storage::delete('public/' . $user->photo);
                }

                $user->photo = $folder . '/' . $filename;
            } catch (\Exception $e) {
                return back()->with('error_photo', 'Error al subir la imagen. Inténtalo de nuevo.');
            }
        }

        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente');
    }

    public function removePhoto()
    {
        $user = Auth::user();
        
        if ($user->photo && Storage::exists('public/' . $user->photo)) {
            Storage::delete('public/' . $user->photo);
        }
        
        $user->photo = null;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Foto eliminada correctamente']);
    }
}
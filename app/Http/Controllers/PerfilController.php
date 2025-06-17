<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $request->validate([
            'name' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048'
        ]);

        $user = Auth::user();

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('perfiles', 'public');
            $user->foto = $fotoPath;
        }

        $user->name = $request->name;
        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente');
    }
}

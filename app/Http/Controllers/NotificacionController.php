<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificacionPersonalizada;
use MongoDB\BSON\ObjectId;

class NotificacionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Debug: verificar el ID del usuario
        \Log::info('Usuario ID consultando notificaciones: ' . $user->_id);
        
        // Marcar todas como leídas
        \App\Models\NotificacionPersonalizada::where('user_id', new \MongoDB\BSON\ObjectId($user->_id))
            ->where('leida', false)
            ->update(['leida' => true]);
            
        $notificaciones = \App\Models\NotificacionPersonalizada::where('user_id', new \MongoDB\BSON\ObjectId($user->_id))
            ->orderBy('created_at', 'desc')
            ->get();
            
        \Log::info('Notificaciones encontradas: ' . $notificaciones->count());
        
        return view('notificaciones.index', compact('notificaciones'));
    }

    public function eliminar($id)
    {
        $user = auth()->user();
        
        // Eliminar solo si la notificación pertenece al usuario autenticado
        NotificacionPersonalizada::where('_id', new ObjectId($id))
            ->where('user_id', new ObjectId($user->_id))
            ->delete();

        return redirect()->route('notificaciones.index')->with('success', 'Notificación eliminada correctamente.');
    }

    public function eliminarTodas()
    {
        $user = auth()->user();
        
        // Eliminar todas las notificaciones del usuario autenticado
        NotificacionPersonalizada::where('user_id', new ObjectId($user->_id))->delete();

        return redirect()->route('notificaciones.index')->with('success', 'Todas las notificaciones han sido eliminadas.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificacionPersonalizada;

class NotificacionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // Marcar todas como leídas
        \App\Models\NotificacionPersonalizada::where('user_id', $user->_id)
            ->where('leida', false)
            ->update(['leida' => true]);
        $notificaciones = \App\Models\NotificacionPersonalizada::where('user_id', $user->_id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('notificaciones.index', compact('notificaciones'));
    }
}

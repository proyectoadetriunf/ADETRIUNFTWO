<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOTecnicoOnly
{
    public function handle(Request $request, Closure $next)
    {
        $rol = auth()->user()->rol_id;

        // Acepta 'admin' o 'moderador' (si quieres que técnico sea 'moderador')
        if ($rol !== 'admin' && $rol !== 'moderador') {
            abort(403, 'Acceso restringido. Solo administradores o técnicos.');
        }

        return $next($request);
    }
}

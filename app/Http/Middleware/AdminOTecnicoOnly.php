<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOTecnicoOnly
{
    public function handle(Request $request, Closure $next)
    {
        $rol = auth()->user()->rol_id;

        // ✅ Acepta 1 (admin) o 2 (técnico)
        if ($rol != 1 && $rol != 2) {
            abort(403, 'Acceso restringido. Solo administradores o técnicos.');
        }

        return $next($request);
    }
}

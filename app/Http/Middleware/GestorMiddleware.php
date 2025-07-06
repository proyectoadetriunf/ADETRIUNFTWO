<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class GestorMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->rol_id === 'moderador') {
            return $next($request);
        }

        abort(403, 'Acceso restringido. Solo moderadores.');
    }
}

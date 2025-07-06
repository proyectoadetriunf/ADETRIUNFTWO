<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->rol_id === 'admin') {
            return $next($request);
        }

        abort(403, 'Acceso restringido. Solo administradores.');
    }
}

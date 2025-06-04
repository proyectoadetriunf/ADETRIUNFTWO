<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RolTecnico
{
    public function handle(Request $request, Closure $next)
    {
        $roles = (array) auth()->user()->rol_id;

        if (!in_array(2, $roles)) {
            abort(403, 'Solo técnicos pueden acceder.');
        }

        return $next($request);
    }
}

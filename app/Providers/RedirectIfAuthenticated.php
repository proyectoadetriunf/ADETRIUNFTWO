<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (Auth::check()) {
            if (Auth::user()->rol === 'admin') {
                return redirect('/admin/usuarios');
            }

            if (Auth::user()->rol === 'moderador') {
                return redirect('/gestor/proyectos');
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionTimeout
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = Session::get('last_activity');
            if ($lastActivity && now()->diffInMinutes($lastActivity) > 15) {
                Auth::logout();
                Session::flush();
                return redirect('/login')->withErrors(['message' => 'Sesión expirada.']);
            }
            Session::put('last_activity', now());
        }
        return $next($request);
    }
}
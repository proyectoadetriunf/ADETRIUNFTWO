<?php
// app/Http/Middleware/CheckPageActive.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class CheckPageActive
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user && in_array(strtolower(trim($user->rol_id)), ['moderador', 'gestor'])) {
            $setting = Setting::where('key', 'pagina_activa')->first();
            if ($setting && $setting->value === '0') {
                return response()->view('pagina-inactiva');
            }
        }
        return $next($request);
    }
}

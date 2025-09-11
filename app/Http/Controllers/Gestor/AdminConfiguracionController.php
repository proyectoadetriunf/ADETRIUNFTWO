<?php
// app/Http/Controllers/Gestor/AdminConfiguracionController.php
namespace App\Http\Controllers\Gestor;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Controllers\Controller;

class AdminConfiguracionController extends Controller
{
    public function activarDesactivar()
    {
        $setting = Setting::firstOrCreate(['key' => 'pagina_activa'], ['value' => '1']);
        return view('admin.activar-desactivar', ['activa' => $setting->value === '1']);
    }

    public function toggle(Request $request)
    {
        $setting = Setting::firstOrCreate(['key' => 'pagina_activa'], ['value' => '1']);
        $setting->value = $setting->value === '1' ? '0' : '1';
        $setting->save();
        return redirect()->back();
    }
}

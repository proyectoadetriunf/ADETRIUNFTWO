<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\BeneficiarioController;
use App\Http\Controllers\AvanceController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PerfilController;

// ====================================
// RUTA BASE Y AUTENTICACIÓN
// ====================================
Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// ====================================
// PANEL PRINCIPAL (SOLO LOGUEADOS CON ROL)
// ====================================
Route::middleware(['auth', 'adminOTecnicoOnly'])->get('/home', [HomeController::class, 'index'])->name('home');

// ====================================
// RUTAS PARA ADMIN Y TÉCNICOS
// ====================================
Route::middleware(['auth', 'adminOTecnicoOnly'])->group(function () {

    // ========== USUARIOS ==========
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);

    // ========== BENEFICIARIOS ==========
    Route::get('/beneficiarios', [BeneficiarioController::class, 'index'])->name('beneficiarios.index');
    Route::post('/beneficiarios', [BeneficiarioController::class, 'store'])->name('beneficiarios.store');
    Route::get('/beneficiarios/{id}/edit', [BeneficiarioController::class, 'edit'])->name('beneficiarios.edit');
    Route::put('/beneficiarios/{id}', [BeneficiarioController::class, 'update'])->name('beneficiarios.update');
    Route::delete('/beneficiarios/{id}', [BeneficiarioController::class, 'destroy'])->name('beneficiarios.destroy');

    // Select dependientes
    Route::get('/api/municipios/{departamento_id}', [BeneficiarioController::class, 'obtenerMunicipios']);
    Route::get('/api/colonias/{municipio_id}', [BeneficiarioController::class, 'obtenerColonias']);

    // ========== PROYECTOS ==========
    Route::get('/proyectos/create', [ProyectoController::class, 'create'])->name('proyectos.create');
    Route::post('/proyectos/store', [ProyectoController::class, 'store'])->name('proyectos.store');

    // ========== AVANCES ==========
    Route::get('/avances/create', [AvanceController::class, 'create'])->name('avances.create');

    // ========== DOCUMENTOS ==========
    Route::get('/documentos/upload', [DocumentoController::class, 'upload'])->name('documentos.upload');

    // ========== SOLICITUDES ==========
    Route::get('/solicitudes/create', [SolicitudController::class, 'create'])->name('solicitudes.create');
});

// ====================================
// CALENDARIO (GENÉRICO PARA AUTENTICADOS)
// ====================================
Route::middleware('auth')->get('/calendario', function () {
    return view('pages.calendar');
});

Route::get('/mapa', [TuControlador::class, 'mostrarMapa'])->name('mapa');

//mensajeria 
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/mensaje', [ChatController::class, 'store'])->name('chat.store');
Route::delete('/chat/mensaje/{id}', [ChatController::class, 'destroy'])->name('chat.destroy');


// perfil
Route::get('perfil/edit', [PerfilController::class, 'edit'])->name('perfil.edit');
Route::post('perfil/update', [PerfilController::class, 'update'])->name('perfil.update');

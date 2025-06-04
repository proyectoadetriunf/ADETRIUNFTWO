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

// ====================================
// RUTA BASE Y AUTENTICACIÓN
// ====================================
Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// ====================================
// RUTA PRINCIPAL (SOLO ADMIN O TÉCNICO)
// ====================================
Route::middleware(['auth', 'adminOTecnicoOnly'])->get('/home', [HomeController::class, 'index'])->name('home');


// ====================================
// RUTAS SOLO PARA ADMINISTRADORES (rol_id = 1)
// ====================================
Route::middleware(['auth', 'adminOTecnicoOnly'])->group(function () {
    // Gestión de usuarios
    Route::get('/usuarios', [UserController::class, 'index']);
    Route::post('/usuarios', [UserController::class, 'store']);

    // Gestión de beneficiarios (ver listado)
    Route::get('/beneficiarios', [BeneficiarioController::class, 'index'])->name('beneficiarios.index');

    // Proyectos
    Route::get('/proyectos/create', [ProyectoController::class, 'create'])->name('proyectos.create');
    Route::post('/proyectos/store', [ProyectoController::class, 'store'])->name('proyectos.store');

    // Avances
    Route::get('/avances/create', [AvanceController::class, 'create'])->name('avances.create');

    // Documentos
    Route::get('/documentos/upload', [DocumentoController::class, 'upload'])->name('documentos.upload');

    // Solicitudes
    Route::get('/solicitudes/create', [SolicitudController::class, 'create'])->name('solicitudes.create');
});

// ====================================
// RUTAS QUE TAMBIÉN PUEDE VER EL TÉCNICO
// ====================================
Route::middleware(['auth', 'adminOTecnicoOnly'])->group(function () {
    Route::get('/beneficiarios/create', [BeneficiarioController::class, 'create'])->name('beneficiarios.create');
    Route::post('/beneficiarios', [BeneficiarioController::class, 'store'])->name('beneficiarios.store');
    Route::get('/beneficiarios/{id}/edit', [BeneficiarioController::class, 'edit'])->name('beneficiarios.edit');
    Route::put('/beneficiarios/{id}', [BeneficiarioController::class, 'update'])->name('beneficiarios.update');
    Route::delete('/beneficiarios/{id}', [BeneficiarioController::class, 'destroy'])->name('beneficiarios.destroy');
});

// ====================================
// AJAX: MUNICIPIOS Y COLONIAS
// ====================================
Route::get('/api/municipios/{departamento}', [BeneficiarioController::class, 'municipiosPorDepartamento']);
Route::get('/api/colonias/{municipio}', [BeneficiarioController::class, 'coloniasPorMunicipio']);

// ====================================
// CALENDARIO (ACCESO GENERAL AUTENTICADO)
// ====================================
Route::middleware('auth')->get('/calendario', function () {
    return view('pages.calendar');
});

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// =======================
//  CONTROLADORES GLOBAL
// =======================
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeneficiarioController;
use App\Http\Controllers\AvanceController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TuControlador; // Ajusta si es necesario

// =======================
//  CONTROLADORES ADMIN
// =======================
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\CitaController as AdminCitaController;
use App\Http\Controllers\Admin\ComunidadController;
use App\Http\Controllers\Admin\ConfigController;

// =======================
//  CONTROLADORES GESTOR
// =======================
use App\Http\Controllers\Gestor\ProyectoController;
use App\Http\Controllers\Gestor\TareaController;
use App\Http\Controllers\Gestor\CitasController;
use App\Http\Controllers\Gestor\BenelisController;
use App\Http\Controllers\Gestor\DocumentosController;
use App\Http\Controllers\Gestor\ResumenController;
use App\Http\Controllers\SalonController;

// ====================================
// RUTA BASE Y AUTENTICACIÓN
// ====================================
Route::get('/', fn () => redirect()->route('login'));
Auth::routes();
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// ====================================
// USUARIOS (resource controller)
// ====================================
Route::resource('usuarios', UsuarioController::class)->names('usuarios');

/*********************** Gestor de proyectos ******************************/
Route::prefix('gestor/proyectos')->group(function () {
    Route::get('/',                       [ProyectoController::class, 'index'])->name('gestor.proyectos.index');
    Route::get('/crear',                  [ProyectoController::class, 'crear'])->name('gestor.proyectos.crear');
    Route::get('/seguimiento',            [ProyectoController::class, 'seguimiento'])->name('gestor.proyectos.seguimiento');
    Route::get('/evidencias',             [ProyectoController::class, 'evidencias'])->name('gestor.proyectos.evidencias');
    Route::post('/',                      [ProyectoController::class, 'store'])->name('gestor.proyectos.store');
    Route::post('/{id}/seguimiento',      [ProyectoController::class, 'agregarSeguimiento'])->name('gestor.proyectos.agregarSeguimiento');
    Route::get('/seguimiento/{id}',       [ProyectoController::class, 'verSeguimientos'])->name('gestor.proyectos.verSeguimientos');
    Route::post('/evidencias/guardar',    [ProyectoController::class, 'guardarEvidencia'])->name('gestor.proyectos.evidencias.guardar');
    Route::post('/asignar',               [ProyectoController::class, 'asignar'])->name('gestor.proyectos.asignar');
});

/********************************** Tareas *********************************/
Route::prefix('gestor/tareas')->group(function () {
    Route::get('/',                 [TareaController::class, 'index'])->name('gestor.tareas.index');
    Route::post('/guardar',         [TareaController::class, 'guardar'])->name('gestor.tareas.guardar');
    Route::post('/{id}/completar',  [TareaController::class, 'completar'])->name('gestor.tareas.completar');
});

/********************************** Citas **********************************/
Route::prefix('gestor/citas')->group(function () {
    Route::get('/',           [CitasController::class, 'index'])->name('gestor.citas.index');
    Route::post('/guardar',   [CitasController::class, 'guardar'])->name('gestor.citas.guardar');
});

/********************************* Beneficiarios ***************************/
Route::prefix('gestor/beneficiarios')->group(function () {
    Route::get('/',                          [BenelisController::class, 'index'])->name('beneficiarios.index');
    Route::get('/encuesta/{id}',             [BenelisController::class, 'encuesta'])->name('beneficiarios.encuesta');
    Route::post('/encuesta/{id}/guardar',    [BenelisController::class, 'guardarEncuesta'])->name('beneficiarios.guardarEncuesta');
});

/******************************** Documentos *******************************/
Route::prefix('gestor/documentos')->group(function () {
    Route::get('/',                [DocumentosController::class, 'index'])->name('documentos.index');
    Route::get('/exportar/{tipo}', [DocumentosController::class, 'exportar'])->name('documentos.exportar');
});

/***************************** Resumen general *****************************/
Route::get('gestor/dashboard', [ResumenController::class, 'index'])->name('resumen.index');

/********************************** Salon **********************************/
Route::prefix('gestor/salon')->middleware(['auth'])->group(function () {
    Route::get('/',            [SalonController::class, 'index'])->name('gestor.salon.index');
    Route::post('/guardar',    [SalonController::class, 'guardar'])->name('gestor.salon.guardar');
});

/**************************** Panel Principal ******************************/
Route::middleware(['auth', 'adminOTecnicoOnly'])->get('/home', [HomeController::class, 'index'])->name('home');

// ====================================
// RUTAS PARA ADMIN Y TÉCNICOS
// ====================================
Route::middleware(['auth', 'adminOTecnicoOnly'])->group(function () {
    // Usuarios
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);

    // Beneficiarios CRUD
    Route::get('/beneficiarios',                 [BeneficiarioController::class, 'index'])->name('beneficiarios.index.panel');
    Route::post('/beneficiarios',                [BeneficiarioController::class, 'store'])->name('beneficiarios.store');
    Route::get('/beneficiarios/{id}/edit',       [BeneficiarioController::class, 'edit'])->name('beneficiarios.edit');
    Route::put('/beneficiarios/{id}',            [BeneficiarioController::class, 'update'])->name('beneficiarios.update');
    Route::delete('/beneficiarios/{id}',         [BeneficiarioController::class, 'destroy'])->name('beneficiarios.destroy');

    // Select dependientes
    Route::get('/api/municipios/{departamento_id}', [BeneficiarioController::class, 'obtenerMunicipios']);
    Route::get('/api/colonias/{municipio_id}',      [BeneficiarioController::class, 'obtenerColonias']);

    // Avances
    Route::get('/avances/create', [AvanceController::class, 'create'])->name('avances.create');

    // Documentos
    Route::get('/documentos/upload', [DocumentoController::class, 'upload'])->name('documentos.upload');

    // Solicitudes
    Route::get('/solicitudes/create', [SolicitudController::class, 'create'])->name('solicitudes.create');
});

// ====================================
// Calendario y utilidades
// ====================================
Route::middleware('auth')->get('/calendario', fn () => view('pages.calendar'));
Route::get('/mapa', [TuControlador::class, 'mostrarMapa'])->name('mapa');

// ====================================
// Mensajería
// ====================================
Route::get('/chat',              [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/mensaje',     [ChatController::class, 'store'])->name('chat.store');
Route::delete('/chat/mensaje/{id}', [ChatController::class, 'destroy'])->name('chat.destroy');

// ====================================
// Perfil
// ====================================
Route::get('perfil/edit',    [PerfilController::class, 'edit'])->name('perfil.edit');
Route::post('perfil/update', [PerfilController::class, 'update'])->name('perfil.update');

// ====================================
// RUTAS SOLO PARA ADMINISTRADOR
// ====================================
Route::middleware(['auth', 'adminonly'])->prefix('admin')->group(function () {
    // Usuarios
    Route::resource('usuarios', UsuarioController::class)->names('usuarios');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // Roles
    Route::get('/roles',                 [RolController::class, 'index'])->name('admin.roles');
    Route::get('/roles/create',          [RolController::class, 'create'])->name('admin.roles.create');
    Route::post('/roles/store',          [RolController::class, 'store'])->name('admin.roles.store');
    Route::get('/roles/{id}/edit',       [RolController::class, 'edit'])->name('admin.roles.edit');
    Route::put('/roles/{id}',            [RolController::class, 'update'])->name('admin.roles.update');
    Route::delete('/roles/{id}',         [RolController::class, 'destroy'])->name('admin.roles.destroy');
    Route::post('/roles/asignar',        [RolController::class, 'asignar'])->name('admin.roles.asignar');

    // Reportes
    Route::get('/reportes/avances',      [ReporteController::class, 'avances'])->name('admin.reportes.avances');
    Route::get('/reportes/financieros',  [ReporteController::class, 'financieros'])->name('admin.reportes.financieros');
    Route::get('/reportes/comunidades',  [ReporteController::class, 'comunidades'])->name('admin.reportes.comunidades');

    // Citas
    Route::get('/citas',                 [AdminCitaController::class, 'index'])->name('admin.citas');

    // Comunidades
    Route::get('/comunidades',           [ComunidadController::class, 'index'])->name('admin.comunidades');

    // Configuración
    Route::get('/configuraciones',       [ConfigController::class, 'index'])->name('admin.configuraciones');
    Route::post('/configuraciones/guardar', [ConfigController::class, 'guardarConfiguraciones'])->name('admin.configuraciones.guardar');
});

// ====================================
// RUTA PÚBLICA PARA MANTENIMIENTO
// ====================================
Route::get('/mantenimiento', function () {
    return view('mantenimiento');
})->name('mantenimiento');

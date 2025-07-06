<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Models\User;

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
use App\Http\Controllers\TuControlador;

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
// PANEL PRINCIPAL
// ====================================
Route::middleware(['auth', 'adminOTecnicoOnly'])->get('/home', [HomeController::class, 'index'])->name('home');

// ====================================
// USUARIOS
// ====================================
Route::middleware(['auth', 'adminonly'])->prefix('admin')->group(function () {
    Route::resource('usuarios', UsuarioController::class)->names('usuarios');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
});

// ====================================
// GESTOR DE PROYECTOS
// ====================================
Route::prefix('gestor/proyectos')->middleware('auth')->group(function () {
    Route::get('/',                       [ProyectoController::class, 'index'])->name('gestor.proyectos.index');
    Route::get('/crear',                  [ProyectoController::class, 'crear'])->name('gestor.proyectos.crear');
    Route::get('/seguimiento',            [ProyectoController::class, 'seguimiento'])->name('gestor.proyectos.seguimiento');
    Route::get('/evidencias',             [ProyectoController::class, 'evidencias'])->name('gestor.proyectos.evidencias');
    Route::post('/',                      [ProyectoController::class, 'store'])->name('gestor.proyectos.store');
    Route::post('/{id}/seguimiento',      [ProyectoController::class, 'agregarSeguimiento'])->name('gestor.proyectos.agregarSeguimiento');
    Route::get('/seguimiento/{id}',       [ProyectoController::class, 'verSeguimientos'])->name('gestor.proyectos.verSeguimientos');
    Route::post('/evidencias/guardar',    [ProyectoController::class, 'guardarEvidencia'])->name('gestor.proyectos.evidencias.guardar');
    Route::post('/asignar',               [ProyectoController::class, 'asignar'])->name('gestor.proyectos.asignar');
    Route::get('/asignados',              [ProyectoController::class, 'asignados'])->name('gestor.asignados');
    Route::post('/asignados/{proyecto}/evidencia', [ProyectoController::class, 'subirEvidencia'])->name('gestor.evidencia.subir');
    Route::get('/asignados/{proyecto}/evidencia/{archivo}', [ProyectoController::class, 'descargarEvidencia'])->name('gestor.evidencia.descargar');
    Route::delete('/asignados/{proyecto}/evidencia/{archivo}', [ProyectoController::class, 'eliminarEvidencia'])->name('gestor.evidencia.eliminar');
});

// ====================================
// GESTOR: TAREAS
// ====================================
Route::prefix('gestor/tareas')->middleware('auth')->group(function () {
    Route::get('/',                 [TareaController::class, 'index'])->name('gestor.tareas.index');
    Route::post('/guardar',         [TareaController::class, 'guardar'])->name('gestor.tareas.guardar');
    Route::post('/{id}/completar',  [TareaController::class, 'completar'])->name('gestor.tareas.completar');
});

// ====================================
// GESTOR: CITAS
// ====================================
Route::prefix('gestor/citas')->middleware('auth')->group(function () {
    Route::get('/',           [CitasController::class, 'index'])->name('gestor.citas.index');
    Route::post('/guardar',   [CitasController::class, 'guardar'])->name('gestor.citas.guardar');
});

// ====================================
// GESTOR: BENEFICIARIOS
// ====================================
Route::prefix('gestor/beneficiarios')->middleware('auth')->group(function () {
    Route::get('/',                          [BenelisController::class, 'index'])->name('beneficiarios.index');
    Route::get('/encuesta/{id}',             [BenelisController::class, 'encuesta'])->name('beneficiarios.encuesta');
    Route::post('/encuesta/{id}/guardar',    [BenelisController::class, 'guardarEncuesta'])->name('beneficiarios.guardarEncuesta');
});

// ====================================
// GESTOR: DOCUMENTOS
// ====================================
Route::prefix('gestor/documentos')->middleware('auth')->group(function () {
    Route::get('/',                [DocumentosController::class, 'index'])->name('documentos.index');
    Route::get('/exportar/{tipo}', [DocumentosController::class, 'exportar'])->name('documentos.exportar');
});

// ====================================
// GESTOR: OTROS
// ====================================
Route::get('gestor/dashboard', [ResumenController::class, 'index'])->name('resumen.index');
Route::prefix('gestor/salon')->middleware('auth')->group(function () {
    Route::get('/',            [SalonController::class, 'index'])->name('gestor.salon.index');
    Route::post('/guardar',    [SalonController::class, 'guardar'])->name('gestor.salon.guardar');
});
Route::prefix('gestor')->middleware('gestor')->group(function () {
    Route::get('/comuni', [App\Http\Controllers\Gestor\ComuniController::class, 'index'])->name('gestor.comuni');
});

// ====================================
// CRUD Beneficiarios (para admin/técnico)
// ====================================
Route::middleware(['auth', 'adminOTecnicoOnly'])->group(function () {
    Route::get('/beneficiarios', [BeneficiarioController::class, 'index'])->name('beneficiarios.index.panel');
    Route::post('/beneficiarios', [BeneficiarioController::class, 'store'])->name('beneficiarios.store');
    Route::get('/beneficiarios/{id}/edit', [BeneficiarioController::class, 'edit'])->name('beneficiarios.edit');
    Route::put('/beneficiarios/{id}', [BeneficiarioController::class, 'update'])->name('beneficiarios.update');
    Route::delete('/beneficiarios/{id}', [BeneficiarioController::class, 'destroy'])->name('beneficiarios.destroy');

    Route::get('/api/municipios/{departamento_id}', [BeneficiarioController::class, 'obtenerMunicipios']);
    Route::get('/api/colonias/{municipio_id}', [BeneficiarioController::class, 'obtenerColonias']);
});

// ====================================
// AVANCES, DOCUMENTOS, SOLICITUDES
// ====================================
Route::get('/avances/create', [AvanceController::class, 'create'])->middleware('auth')->name('avances.create');
Route::get('/documentos/upload', [DocumentoController::class, 'upload'])->middleware('auth')->name('documentos.upload');
Route::get('/solicitudes/create', [SolicitudController::class, 'create'])->middleware('auth')->name('solicitudes.create');

// ====================================
// PERFIL, CHAT Y MAPA
// ====================================
Route::get('/perfil/edit', [PerfilController::class, 'edit'])->name('perfil.edit');
Route::post('/perfil/update', [PerfilController::class, 'update'])->name('perfil.update');

Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/mensaje', [ChatController::class, 'store'])->name('chat.store');
Route::delete('/chat/mensaje/{id}', [ChatController::class, 'destroy'])->name('chat.destroy');

Route::middleware('auth')->get('/calendario', fn () => view('pages.calendar'));
Route::get('/mapa', [TuControlador::class, 'mostrarMapa'])->name('mapa');

// ====================================
// ADMIN: ROLES, REPORTES, CONFIGURACIÓN
// ====================================
Route::middleware(['auth', 'adminonly'])->prefix('admin')->group(function () {
    Route::get('/roles', [RolController::class, 'index'])->name('admin.roles');
    Route::get('/roles/create', [RolController::class, 'create'])->name('admin.roles.create');
    Route::post('/roles/store', [RolController::class, 'store'])->name('admin.roles.store');
    Route::get('/roles/{id}/edit', [RolController::class, 'edit'])->name('admin.roles.edit');
    Route::put('/roles/{id}', [RolController::class, 'update'])->name('admin.roles.update');
    Route::delete('/roles/{id}', [RolController::class, 'destroy'])->name('admin.roles.destroy');
    Route::post('/roles/asignar', [RolController::class, 'asignar'])->name('admin.roles.asignar');

    Route::get('/reportes/avances', [ReporteController::class, 'avances'])->name('admin.reportes.avances');
    Route::get('/reportes/financieros', [ReporteController::class, 'financieros'])->name('admin.reportes.financieros');
    Route::get('/reportes/comunidades', [ReporteController::class, 'comunidades'])->name('admin.reportes.comunidades');

    Route::get('/citas', [AdminCitaController::class, 'index'])->name('admin.citas');
    Route::get('/comunidades', [ComunidadController::class, 'index'])->name('admin.comunidades');

    Route::get('/configuraciones', [ConfigController::class, 'index'])->name('admin.configuraciones');
    Route::post('/configuraciones/guardar', [ConfigController::class, 'guardarConfiguraciones'])->name('admin.configuraciones.guardar');
});

// ====================================
// NOTIFICACIONES
// ====================================
Route::get('/notificaciones', [App\Http\Controllers\NotificacionController::class, 'index'])->middleware('auth')->name('notificaciones');

// ====================================
// TEST DE CORREO
// ====================================
Route::get('/test-email', function () {
    $user = User::where('email', 'nery.varela@gmail.com')->first();

    if (!$user) return '❌ Usuario no encontrado.';
    $token = Password::createToken($user);
    $user->sendPasswordResetNotification($token);
    return '✅ Correo de recuperación enviado correctamente.';
});

// ====================================
// PÁGINA DE MANTENIMIENTO
// ====================================
Route::get('/mantenimiento', function () {
    return view('mantenimiento');
})->name('mantenimiento');

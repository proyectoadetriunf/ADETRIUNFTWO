<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
<<<<<<< HEAD
=======

// =======================
//  CONTROLADORES GLOBAL
// =======================
>>>>>>> 5a18b9c5d62b3e7d53f58f3debb1a83b8f8029bc
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeneficiarioController;
use App\Http\Controllers\AvanceController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TuControlador; // ← ajusta al nombre real

// =======================
//  CONTROLADORES ADMIN
// =======================
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\RolController;
<<<<<<< HEAD

=======
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\CitaController as AdminCitaController;
use App\Http\Controllers\Admin\ComunidadController;
use App\Http\Controllers\Admin\ConfigController;
>>>>>>> 5a18b9c5d62b3e7d53f58f3debb1a83b8f8029bc

// =======================
//  CONTROLADORES GESTOR
// =======================
use App\Http\Controllers\Gestor\ProyectoController as GestorProyectoController;
<<<<<<< HEAD

Route::prefix('gestor/proyectos')->group(function () {
    Route::get('/', [GestorProyectoController::class, 'index'])->name('gestor.proyectos.index');
    Route::get('/crear', [GestorProyectoController::class, 'crear'])->name('gestor.proyectos.crear');
    Route::get('/seguimiento', [GestorProyectoController::class, 'seguimiento'])->name('gestor.proyectos.seguimiento');
    Route::get('/evidencias', [GestorProyectoController::class, 'evidencias'])->name('gestor.proyectos.evidencias');
    Route::post('/', [GestorProyectoController::class, 'store'])->name('gestor.proyectos.store');
    Route::post('/{id}/seguimiento', [GestorProyectoController::class, 'agregarSeguimiento'])->name('gestor.proyectos.agregarSeguimiento');
    Route::get('/seguimiento/{id}', [GestorProyectoController::class, 'verSeguimientos'])->name('gestor.proyectos.verSeguimientos');
    Route::post('/evidencias/guardar', [GestorProyectoController::class, 'guardarEvidencia'])->name('gestor.proyectos.evidencias.guardar');
   
});

 Route::prefix('gestor')->middleware('gestor')->group(function () {
    Route::get('/comuni', [App\Http\Controllers\gestor\ComuniController::class, 'index'])->name('gestor.comuni');
});

Route::post('/asignar', [App\Http\Controllers\ProyectoController::class, 'asignar'])->name('gestor.proyectos.asignar');

use App\Http\Controllers\Gestor\ProyectoController;


Route::prefix('gestor/proyectos')->name('gestor.proyectos.')->group(function() {
    Route::get('/', [ProyectoController::class, 'index'])->name('index');
    Route::post('/store', [ProyectoController::class, 'store'])->name('store');
    Route::post('/asignar', [ProyectoController::class, 'asignar'])->name('asignar');
    Route::post('/evidencias/guardar', [ProyectoController::class, 'guardarEvidencia'])->name('evidencias.guardar');
});



/***********************************************************************/

/**********************************Salon******************************** */
// Rutas de gestión del salón
Route::prefix('gestor/salon')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\SalonController::class, 'index'])->name('gestor.salon.index');
    Route::post('/guardar', [App\Http\Controllers\SalonController::class, 'guardar'])->name('gestor.salon.guardar');
});




/*********************************************************************** */
/**********************************tareas***********************************************/

use App\Http\Controllers\Gestor\TareaController;
Route::prefix('gestor/tareas')->group(function () {
    // Vista principal de tareas/actividades
    Route::get('/', [TareaController::class, 'index'])->name('gestor.tareas.index');

    // Guardar nueva tarea
    Route::post('/guardar', [TareaController::class, 'guardar'])->name('gestor.tareas.guardar');

    // Cambiar estado de una tarea (ej: Pendiente -> Completada)
    Route::post('/{id}/completar', [TareaController::class, 'completar'])->name('gestor.tareas.completar');
});

/***************************************************************************************/

/**********************************citas********************************************** */
use App\Http\Controllers\CitasController;

Route::prefix('gestor/citas')->middleware(['auth'])->group(function () {
    Route::get('/', [CitasController::class, 'index'])->name('gestor.citas.index');
    Route::post('/guardar', [CitasController::class, 'guardar'])->name('gestor.citas.guardar');
});

    

/*********************************beneficiarios******************************************** */

Route::prefix('gestor/beneficiarios')->group(function () {
    Route::get('/', [App\Http\Controllers\Gestor\BenelisController::class, 'index'])->name('beneficiarios.index');
    Route::get('/encuesta/{id}', [App\Http\Controllers\Gestor\BenelisController::class, 'encuesta'])->name('beneficiarios.encuesta');
});
Route::prefix('gestor/beneficiarios')->group(function () {
    Route::get('/', [App\Http\Controllers\Gestor\BenelisController::class, 'index'])->name('beneficiarios.index');
    Route::get('/encuesta/{id}', [App\Http\Controllers\Gestor\BenelisController::class, 'encuesta'])->name('beneficiarios.encuesta');
    Route::post('/encuesta/{id}/guardar', [App\Http\Controllers\Gestor\BenelisController::class, 'guardarEncuesta'])->name('beneficiarios.guardarEncuesta');
});
Route::get('/gestor/documentos', [App\Http\Controllers\Gestor\DocumentosController::class, 'index'])->name('documentos.index');
Route::get('/gestor/documentos/exportar/{tipo}', [App\Http\Controllers\Gestor\DocumentosController::class, 'exportar'])->name('documentos.exportar');
    Route::get('/gestor/documentos/exportar/{tipo}', [App\Http\Controllers\Gestor\DocumentosController::class, 'exportar'])->name('documentos.exportar');



    Route::get('resumen-general', [ResumenController::class, 'index'])->name('resumen.index');
=======
use App\Http\Controllers\Gestor\TareaController;
use App\Http\Controllers\Gestor\CitasController;
use App\Http\Controllers\Gestor\BenelisController;
use App\Http\Controllers\Gestor\DocumentosController;
>>>>>>> 5a18b9c5d62b3e7d53f58f3debb1a83b8f8029bc
use App\Http\Controllers\Gestor\ResumenController;

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
    Route::get('/',                       [GestorProyectoController::class, 'index'])->name('gestor.proyectos.index');
    Route::get('/crear',                  [GestorProyectoController::class, 'crear'])->name('gestor.proyectos.crear');
    Route::get('/seguimiento',            [GestorProyectoController::class, 'seguimiento'])->name('gestor.proyectos.seguimiento');
    Route::get('/evidencias',             [GestorProyectoController::class, 'evidencias'])->name('gestor.proyectos.evidencias');
    Route::post('/',                      [GestorProyectoController::class, 'store'])->name('gestor.proyectos.store');
    Route::post('/{id}/seguimiento',      [GestorProyectoController::class, 'agregarSeguimiento'])->name('gestor.proyectos.agregarSeguimiento');
    Route::get('/seguimiento/{id}',       [GestorProyectoController::class, 'verSeguimientos'])->name('gestor.proyectos.verSeguimientos');
    Route::post('/evidencias/guardar',    [GestorProyectoController::class, 'guardarEvidencia'])->name('gestor.proyectos.evidencias.guardar');
});

/********************************** Tareas *********************************/
Route::prefix('gestor/tareas')->group(function () {
    Route::get('/',                 [TareaController::class, 'index'   ])->name('gestor.tareas.index');
    Route::post('/guardar',         [TareaController::class, 'guardar' ])->name('gestor.tareas.guardar');
    Route::post('/{id}/completar',  [TareaController::class, 'completar'])->name('gestor.tareas.completar');
});

/********************************** Citas **********************************/
Route::prefix('gestor/citas')->group(function () {
    Route::get('/',           [CitasController::class, 'index'  ])->name('gestor.citas.index');
    Route::post('/guardar',   [CitasController::class, 'guardar'])->name('gestor.citas.guardar');
});

/****************************** Beneficiarios ******************************/
Route::prefix('gestor/beneficiarios')->group(function () {
    Route::get('/',                          [BenelisController::class, 'index'         ])->name('beneficiarios.index');
    Route::get('/encuesta/{id}',             [BenelisController::class, 'encuesta'      ])->name('beneficiarios.encuesta');
    Route::post('/encuesta/{id}/guardar',    [BenelisController::class, 'guardarEncuesta'])->name('beneficiarios.guardarEncuesta');
});

/******************************** Documentos *******************************/
Route::prefix('gestor/documentos')->group(function () {
    Route::get('/',                [DocumentosController::class, 'index'  ])->name('documentos.index');
    Route::get('/exportar/{tipo}', [DocumentosController::class, 'exportar'])->name('documentos.exportar');
});

/***************************** Resumen general *****************************/
Route::get('gestor/dashboard', [ResumenController::class, 'index'])->name('resumen.index');

// ====================================
// PANEL PRINCIPAL (SOLO LOGUEADOS CON ROL)
// ====================================
Route::middleware(['auth', 'adminOTecnicoOnly'])->get('/home', [HomeController::class, 'index'])->name('home');

// ====================================
// RUTAS PARA ADMIN Y TÉCNICOS
// ====================================
Route::middleware(['auth', 'adminOTecnicoOnly'])->group(function () {
    // Usuarios
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);

    // Beneficiarios CRUD
    Route::get('/beneficiarios',                 [BeneficiarioController::class, 'index' ])->name('beneficiarios.index.panel');
    Route::post('/beneficiarios',                [BeneficiarioController::class, 'store' ])->name('beneficiarios.store');
    Route::get('/beneficiarios/{id}/edit',       [BeneficiarioController::class, 'edit'  ])->name('beneficiarios.edit');
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

// Calendario y utilidades auth genérico
Route::middleware('auth')->get('/calendario', fn () => view('pages.calendar'));
Route::get('/mapa', [TuControlador::class, 'mostrarMapa'])->name('mapa');

// Mensajería
Route::get('/chat',              [ChatController::class, 'index'  ])->name('chat.index');
Route::post('/chat/mensaje',     [ChatController::class, 'store'  ])->name('chat.store');
Route::delete('/chat/mensaje/{id}', [ChatController::class, 'destroy'])->name('chat.destroy');

// Perfil
Route::get('perfil/edit',    [PerfilController::class, 'edit'  ])->name('perfil.edit');
Route::post('perfil/update', [PerfilController::class, 'update'])->name('perfil.update');

// ====================================
// RUTAS SOLO PARA ADMINISTRADOR
// ====================================
Route::middleware(['auth', 'adminonly'])->prefix('admin')->group(function () {
    Route::get('/usuarios',          [UsuarioController::class, 'index' ])->name('admin.usuarios');

<<<<<<< HEAD
    // 👥 Gestión de Usuarios
    Route::resource('usuarios', App\Http\Controllers\Admin\UsuarioController::class)->names('usuarios');
    Route::delete('/admin/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    


    // 🔐 CRUD de Roles

Route::get('/roles', [RolController::class, 'index'])->name('admin.roles');
Route::get('/roles/create', [RolController::class, 'create'])->name('admin.roles.create');
Route::post('/roles/store', [RolController::class, 'store'])->name('admin.roles.store');
Route::get('/roles/{id}/edit', [RolController::class, 'edit'])->name('admin.roles.edit');
Route::put('/roles/{id}', [RolController::class, 'update'])->name('admin.roles.update');
Route::delete('/roles/{id}', [RolController::class, 'destroy'])->name('admin.roles.destroy');
Route::post('/roles/asignar', [RolController::class, 'asignar'])->name('admin.roles.asignar');


    // 📊 Reportes
    Route::get('/reportes/avances', [App\Http\Controllers\Admin\ReporteController::class, 'avances'])->name('admin.reportes.avances');
    Route::get('/reportes/financieros', [App\Http\Controllers\Admin\ReporteController::class, 'financieros'])->name('admin.reportes.financieros');
    Route::get('/reportes/comunidades', [App\Http\Controllers\Admin\ReporteController::class, 'comunidades'])->name('admin.reportes.comunidades');

    // 📅 Citas
    Route::get('/citas', [App\Http\Controllers\Admin\CitaController::class, 'index'])->name('admin.citas');

    // 🏘️ Comunidades
    Route::get('/comunidades', [App\Http\Controllers\Admin\ComunidadController::class, 'index'])->name('admin.comunidades');

    // ⚙️ Configuración del sistema
    Route::get('/configuraciones', [App\Http\Controllers\Admin\ConfigController::class, 'index'])->name('admin.configuraciones');
    Route::post('/configuraciones/guardar', [App\Http\Controllers\Admin\ConfigController::class, 'guardarConfiguraciones'])->name('admin.configuraciones.guardar');
});

// ====================================
// RUTA PÚBLICA PARA MANTENIMIENTO
// ====================================
Route::get('/mantenimiento', function () {
    return view('mantenimiento');
})->name('mantenimiento');



=======
    Route::get('/roles',             [RolController::class, 'index'     ])->name('admin.roles');
  Route::post('/roles/asignar', [App\Http\Controllers\Admin\RolController::class, 'asignar'])->name('admin.roles.asignar');
});
>>>>>>> 5a18b9c5d62b3e7d53f58f3debb1a83b8f8029bc

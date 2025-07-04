<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeneficiarioController;
use App\Http\Controllers\AvanceController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PerfilController;

use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\RolController;


Route::resource('usuarios', UsuarioController::class)->names('usuarios');

/***********************Gestor de proyectos******************************/
use App\Http\Controllers\Gestor\ProyectoController as GestorProyectoController;

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
use App\Http\Controllers\Gestor\ResumenController;

Route::get('gestor/dashboard', [ResumenController::class, 'index'])->name('resumen.index');


Route::prefix('gestor')->group(function () {
    Route::get('/citas', [CitasController::class, 'index'])->name('gestor.citas.index');
});
Route::get('/gestor/citas', [CitasController::class, 'index'])->name('gestor.citas.index');



/************************************************************************************* */






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

// ====================================
// RUTAS SOLO PARA ADMINISTRADOR
// ====================================
Route::middleware(['auth', 'adminonly'])->prefix('admin')->group(function () {

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




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
use App\Http\Controllers\Admin\ReporteController;


use App\Http\Controllers\Admin\UsuarioController;

Route::resource('usuarios', UsuarioController::class)->names('usuarios');

/***********************Gestor de proyectos******************************/
use App\Http\Controllers\Gestor\ProyectoController as GestorProyectoController;

Route::prefix('gestor/proyectos')->middleware(['auth', 'check.page.active'])->group(function () {
    Route::get('/', [GestorProyectoController::class, 'index'])->name('gestor.proyectos.index');
    Route::get('/crear', [GestorProyectoController::class, 'crear'])->name('gestor.proyectos.crear');
    Route::get('/seguimiento', [GestorProyectoController::class, 'seguimiento'])->name('gestor.proyectos.seguimiento');
    Route::get('/evidencias', [GestorProyectoController::class, 'evidencias'])->name('gestor.proyectos.evidencias');
    Route::post('/', [GestorProyectoController::class, 'store'])->name('gestor.proyectos.store');
    Route::post('/{id}/seguimiento', [GestorProyectoController::class, 'agregarSeguimiento'])->name('gestor.proyectos.agregarSeguimiento');
    Route::get('/seguimiento/{id}', [GestorProyectoController::class, 'verSeguimientos'])->name('gestor.proyectos.verSeguimientos');
    Route::post('/evidencias/guardar', [GestorProyectoController::class, 'guardarEvidencia'])->name('gestor.proyectos.evidencias.guardar');
   
});

 Route::prefix('gestor')->middleware(['gestor', 'check.page.active'])->group(function () {
    Route::get('/comuni', [App\Http\Controllers\gestor\ComuniController::class, 'index'])->name('gestor.comuni');
});

Route::post('/asignar', [App\Http\Controllers\ProyectoController::class, 'asignar'])->name('gestor.proyectos.asignar');

use App\Http\Controllers\Gestor\ProyectoController;


Route::prefix('gestor/proyectos')->name('gestor.proyectos.')->middleware(['auth', 'check.page.active'])->group(function() {
    Route::get('/', [ProyectoController::class, 'index'])->name('index');
    Route::post('/store', [ProyectoController::class, 'store'])->name('store');
    Route::post('/asignar', [ProyectoController::class, 'asignar'])->name('asignar');
    Route::post('/evidencias/guardar', [ProyectoController::class, 'guardarEvidencia'])->name('evidencias.guardar');
});

Route::prefix('gestor/proyectos')->middleware(['auth', 'check.page.active'])->group(function () {
    Route::get('/', [ProyectoController::class, 'index'])->name('gestor.proyectos.index');
    Route::post('/store', [ProyectoController::class, 'store'])->name('gestor.proyectos.store');
    Route::post('/asignar', [ProyectoController::class, 'asignar'])->name('gestor.proyectos.asignar');

    // ✅ NUEVAS RUTAS PARA EDITAR Y ELIMINAR
    Route::get('/editar/{id}', [ProyectoController::class, 'editar'])->name('gestor.proyectos.editar');
    Route::put('/actualizar/{id}', [ProyectoController::class, 'actualizar'])->name('gestor.proyectos.actualizar');
    Route::delete('/eliminar/{id}', [ProyectoController::class, 'eliminar'])->name('gestor.proyectos.eliminar');
});
Route::get('gestor/proyectos/editar/{id}', [ProyectoController::class, 'editar'])->name('gestor.proyectos.editar')->middleware(['auth', 'check.page.active']);
Route::put('gestor/proyectos/actualizar/{id}', [ProyectoController::class, 'actualizar'])->name('gestor.proyectos.actualizar')->middleware(['auth', 'check.page.active']);
Route::delete('gestor/proyectos/eliminar/{id}', [ProyectoController::class, 'eliminar'])->name('gestor.proyectos.eliminar')->middleware(['auth', 'check.page.active']);
Route::post('gestor/proyectos/asignar', [ProyectoController::class, 'asignar'])->name('gestor.proyectos.asignar')->middleware(['auth', 'check.page.active']);
Route::put('gestor/proyectos/{id}/actualizar', [ProyectoController::class, 'actualizar'])->name('gestor.proyectos.actualizar')->middleware(['auth', 'check.page.active']);
use App\Http\Controllers\Gestor\CronogramaController;

Route::prefix('gestor/proyectos')->middleware(['auth', 'check.page.active'])->group(function () {
    Route::get('cronograma/{proyecto_id}', [CronogramaController::class, 'index'])->name('gestor.proyectos.cronograma');
    Route::post('cronograma/store', [CronogramaController::class, 'store'])->name('gestor.proyectos.cronograma.store');
    Route::post('cronograma/finalizar/{actividad_id}', [CronogramaController::class, 'finalizar'])->name('gestor.proyectos.cronograma.finalizar');
});
Route::get('gestor/asignados', [ProyectoController::class, 'asignados'])->name('gestor.asignados')->middleware(['auth', 'check.page.active']);
Route::get('gestor/asignados/cronograma/{id}', [ProyectoController::class, 'cronograma'])->name('gestor.cronograma')->middleware(['auth', 'check.page.active']);
Route::get('gestor/asignados/avances/{id}', [ProyectoController::class, 'avances'])->name('gestor.avances')->middleware(['auth', 'check.page.active']);
Route::get('gestor/asignados', [ProyectoController::class, 'asignados'])->name('gestor.proyectos.asignados')->middleware(['auth', 'check.page.active']);
Route::get('gestor/asignados/{id}/cronograma', [ProyectoController::class, 'cronograma'])->name('gestor.proyectos.cronograma')->middleware(['auth', 'check.page.active']);
Route::get('gestor/asignados/{id}/avances', [ProyectoController::class, 'avances'])->name('gestor.proyectos.avances')->middleware(['auth', 'check.page.active']);
Route::get('gestor/asignados/cronograma/{id}', [ProyectoController::class, 'cronograma'])->name('gestor.proyectos.cronograma')->middleware(['auth', 'check.page.active']);
Route::post('gestor/proyectos/cronograma/finalizar/{id}', [ProyectoController::class, 'finalizarActividad'])->name('gestor.proyectos.cronograma.finalizar')->middleware(['auth', 'check.page.active']);
Route::post('/gestor/proyectos/avances/guardar', [ProyectoController::class, 'guardarAvance'])
     ->name('gestor.proyectos.avances.guardar')->middleware(['auth', 'check.page.active']);

Route::post('gestor/proyectos/avances/store', [ProyectoController::class, 'guardarAvance'])->name('gestor.proyectos.avances.store')->middleware(['auth', 'check.page.active']);

Route::post('gestor/proyectos/avances/guardar', [ProyectoController::class, 'guardarAvance'])->name('gestor.proyectos.avances.store')->middleware(['auth', 'check.page.active']);
Route::post('/gestor/proyectos/avances/guardar', [ProyectoController::class, 'guardarAvance'])
    ->name('gestor.proyectos.avances.store')->middleware(['auth', 'check.page.active']);
Route::get('gestor/proyectos/avances/{id}', [ProyectoController::class, 'avances'])->name('gestor.proyectos.avances')->middleware(['auth', 'check.page.active']);
Route::post('/gestor/proyectos/avances/guardar', [App\Http\Controllers\Gestor\ProyectoController::class, 'guardarAvance'])
    ->name('gestor.proyectos.avances.guardar')->middleware(['auth', 'check.page.active']);
Route::get('gestor/asignados/cronograma/{id}', [App\Http\Controllers\Gestor\ProyectoController::class, 'cronograma'])
    ->name('gestor.cronograma')->middleware(['auth', 'check.page.active']);
Route::post('/gestor/proyectos/progreso/{id}', [App\Http\Controllers\Gestor\ProyectoController::class, 'actualizarProgreso'])
    ->name('gestor.proyectos.actualizarProgreso')->middleware(['auth', 'check.page.active']);

Route::get('/gestor/proyectos/avances/exportar-word/{id}', [ProyectoController::class, 'exportarWord'])->name('gestor.proyectos.exportarWord')->middleware(['auth', 'check.page.active']);
Route::get('/gestor/proyectos/avances/exportar-excel/{id}', [ProyectoController::class, 'exportarExcel'])->name('gestor.proyectos.exportarExcel')->middleware(['auth', 'check.page.active']);


Route::get('/beneficiarios', [\App\Http\Controllers\Gestor\BenelisController::class, 'index'])->name('beneficiarios.index')->middleware(['auth', 'check.page.active']);
Route::get('/beneficiarios', [BenelisController::class, 'index'])->name('beneficiarios.index')->middleware(['auth', 'check.page.active']);
Route::get('/beneficiarios', [BenelisController::class, 'index'])->name('beneficiarios.index')->middleware(['auth', 'check.page.active']);

use App\Http\Controllers\Gestor\BenelisController;

Route::get('/beneficiarios', [BenelisController::class, 'index'])->name('beneficiarios.index')->middleware(['auth', 'check.page.active']);
Route::get('/beneficiarios/proyectos-asignados', [App\Http\Controllers\Gestor\BenelisController::class, 'proyectosAsignados'])
    ->name('beneficiarios.proyectos.asignados')->middleware(['auth', 'check.page.active']);
Route::get('/beneficiarios/seleccionar-proyecto', [BenelisController::class, 'seleccionarProyecto'])->name('beneficiarios.seleccionar')->middleware(['auth', 'check.page.active']);
Route::get('/beneficiarios/registrar/{proyecto_id}', [BenelisController::class, 'formulario'])->name('beneficiarios.registrar')->middleware(['auth', 'check.page.active']);
Route::get('beneficiarios/seleccionar', [BenelisController::class, 'seleccionarProyecto'])->name('beneficiarios.seleccionar')->middleware(['auth', 'check.page.active']);
Route::get('beneficiarios/formulario/{proyecto_id}', [BenelisController::class, 'formulario'])->name('beneficiarios.formulario')->middleware(['auth', 'check.page.active']);

// Mostrar proyectos asignados para registrar beneficiario
Route::get('beneficiarios/seleccionar-proyecto', [BenelisController::class, 'seleccionarProyecto'])->name('beneficiarios.seleccionar')->middleware(['auth', 'check.page.active']);

// Mostrar formulario de registro con el proyecto ya seleccionado
Route::get('beneficiarios/formulario/{proyecto_id}', [BenelisController::class, 'formulario'])->name('beneficiarios.formulario')->middleware(['auth', 'check.page.active']);

// Guardar beneficiario (esto ya lo debes tener, solo por si acaso)
Route::post('beneficiarios/store', [BenelisController::class, 'store'])->name('beneficiarios.store')->middleware(['auth', 'check.page.active']);
Route::get('beneficiarios/proyectos-asignados', [BenelisController::class, 'proyectosAsignados'])->name('beneficiarios.proyectos.asignados')->middleware(['auth', 'check.page.active']);

/***********************************************************************/
Route::get('/beneficiarios', [App\Http\Controllers\Gestor\BenelisController::class, 'mostrarProyectosAsignados'])->name('beneficiarios.index')->middleware(['auth', 'check.page.active']);
Route::post('/beneficiarios/guardar', [BeneficiarioController::class, 'store'])->name('beneficiarios.guardar')->middleware(['auth', 'check.page.active']);
Route::get('/beneficiarios', [BeneficiarioController::class, 'index'])->name('beneficiarios.index')->middleware(['auth', 'check.page.active']);
Route::get('/beneficiarios/formulario/{proyecto_id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.formulario')->middleware(['auth', 'check.page.active']);
Route::post('/beneficiarios/guardar', [BeneficiarioController::class, 'guardar'])->name('beneficiarios.guardar')->middleware(['auth', 'check.page.active']);
// AJAX para cargar municipios y colonias
Route::get('/beneficiarios/municipios/{id}', [BeneficiarioController::class, 'obtenerMunicipios']);
Route::get('/beneficiarios/colonias/{id}', [BeneficiarioController::class, 'obtenerColonias']);
Route::get('/beneficiarios/registrar/{proyecto_id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.registrar')->middleware(['auth', 'check.page.active']);
Route::get('gestor/beneficiarios/formulario/{proyecto_id}', [BenelisController::class, 'formulario'])->name('gestor.beneficiarios.formulario')->middleware(['auth', 'check.page.active']);
Route::get('/beneficiarios/formulario/{proyecto_id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.formulario');
Route::post('/beneficiarios/guardar', [BeneficiarioController::class, 'store'])->name('beneficiarios.store');
Route::get('/beneficiarios/municipios/{id}', [BeneficiarioController::class, 'obtenerMunicipios']);
Route::get('/beneficiarios/colonias/{id}', [BeneficiarioController::class, 'obtenerColonias']);
Route::prefix('gestor/beneficiarios')->middleware(['auth', 'check.page.active'])->group(function () {
    Route::get('seleccionar', [BenelisController::class, 'seleccionarProyecto'])->name('gestor.beneficiarios.seleccionarProyecto');
    Route::get('formulario/{id}', [BenelisController::class, 'formulario'])->name('gestor.beneficiarios.formulario');
});
Route::get('gestor/beneficiarios/formulario/{proyecto_id}', [BenelisController::class, 'formulario'])->name('gestor.beneficiarios.formulario')->middleware(['auth', 'check.page.active']);

Route::prefix('beneficiarios')->middleware(['auth', 'check.page.active'])->group(function () {
    Route::get('/', [BeneficiarioController::class, 'index'])->name('beneficiarios.index');
    Route::get('/formulario/{proyecto_id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.formulario');
    Route::post('/guardar', [BeneficiarioController::class, 'guardar'])->name('beneficiarios.guardar');
});

Route::get('/beneficiarios', [BenelisController::class, 'index'])->name('beneficiarios.index')->middleware(['auth', 'check.page.active']);
Route::get('/beneficiarios/proyectos-asignados', [BenelisController::class, 'proyectosAsignados'])->name('beneficiarios.proyectos.asignados')->middleware(['auth', 'check.page.active']);



/**********************************Salon******************************** */
// Rutas de gestión del salón
Route::prefix('gestor/salon')->middleware(['auth', 'check.page.active'])->group(function () {
    Route::get('/', [App\Http\Controllers\SalonController::class, 'index'])->name('gestor.salon.index');
    Route::post('/guardar', [App\Http\Controllers\SalonController::class, 'guardar'])->name('gestor.salon.guardar');
});




/*********************************************************************** */
/**********************************solicitudes******************************** */
// Rutas de gestión del salón
Route::get('crear/solicitud', [SolicitudController::class, 'create'])->name('solicitudes.create')->middleware(['auth', 'check.page.active']);
Route::post('crear/solicitud', [SolicitudController::class, 'store'])->name('solicitudes.store')->middleware(['auth', 'check.page.active']);
Route::get('mostrar/solicitudes', [SolicitudController::class, 'mostrar'])->name('solicitudes.mostrar');
Route::post('solicitudes/aceptar/{id}', [SolicitudController::class, 'aceptar'])->name('solicitudes.aceptar');
Route::post('solicitudes/rechazar/{id}', [SolicitudController::class, 'rechazar'])->name('solicitudes.rechazar');
Route::get('solicitudes/exportar', [SolicitudController::class, 'exportar'])->name('solicitudes.exportar');
Route::get('solicitudes/exportar/pdf/{estado}', [SolicitudController::class, 'exportarPdf'])->name('solicitudes.exportar.pdf');
Route::get('solicitudes/exportar/word/{estado}', [SolicitudController::class, 'exportarWord'])->name('solicitudes.exportar.word');
Route::get('mostrar/solicitudes', [SolicitudController::class, 'mostrar'])->name('solicitudes.mostrar');
Route::get('/solicitudes/exportar/pdf/{estado}', [SolicitudController::class, 'exportarPdf'])->name('solicitudes.exportar.pdf');
Route::get('solicitudes/exportar/word/{estado}', [SolicitudController::class, 'exportarWord'])->name('solicitudes.exportar.word');
Route::get('solicitudes/exportar/pdf/{estado}', [SolicitudController::class, 'exportarPdf'])->name('solicitudes.exportar.pdf');
Route::get('solicitudes/exportar/excel/{estado}', [SolicitudController::class, 'exportarExcel'])->name('solicitudes.exportar.excel');
Route::get('solicitudes/exportar/pdf/{estado}', [SolicitudController::class, 'exportarPdf'])->name('solicitudes.exportar.pdf');
Route::get('solicitudes/exportar-html/{estado}', [SolicitudController::class, 'exportarHtml'])->name('solicitudes.exportar.html');
Route::get('solicitudes/exportar-word/{estado}', [SolicitudController::class, 'exportarWord'])->name('solicitudes.exportar.word');
Route::get('solicitudes/exportar', [SolicitudController::class, 'exportar'])->name('solicitudes.exportar');


/*********************************************************************** */
/**********************************tareas***********************************************/

// ADMIN: tareas
use App\Http\Controllers\Admin\TareaController as AdminTareaController;

Route::middleware(['auth'])->prefix('admin/tareas')->group(function () {
    Route::get('/', [AdminTareaController::class, 'index'])->name('admin.tareas.index');
    Route::post('/guardar', [AdminTareaController::class, 'guardar'])->name('admin.tareas.guardar');
    Route::post('/{id}/completar', [AdminTareaController::class, 'completar'])->name('admin.tareas.completar');
    Route::delete('/{id}/eliminar', [AdminTareaController::class, 'eliminar'])->name('admin.tareas.eliminar'); // ✅ NUEVA RUTA
});


use App\Http\Controllers\Gestor\TareaController as GestorTareaController;

Route::prefix('gestor/tareas')->middleware(['auth', 'check.page.active'])->group(function () {
    Route::get('/', [GestorTareaController::class, 'misTareas'])->name('gestor.tareas.mis-tareas');
    Route::post('/{id}/finalizar', [GestorTareaController::class, 'finalizarTarea'])->name('gestor.tareas.finalizar');
});



/***************************************************************************************/

/**********************************citas********************************************** */
use App\Http\Controllers\CitasController;

Route::prefix('gestor/citas')->middleware(['auth', 'check.page.active'])->group(function () {
    Route::get('/', [CitasController::class, 'index'])->name('gestor.citas.index');
    Route::post('/guardar', [CitasController::class, 'guardar'])->name('gestor.citas.guardar');
});

    

/*********************************beneficiarios******************************************** */

Route::prefix('gestor/beneficiarios')->middleware(['auth', 'check.page.active'])->group(function () {
    Route::get('/', [App\Http\Controllers\Gestor\BenelisController::class, 'index'])->name('beneficiarios.index');
    Route::get('/encuesta/{id}', [App\Http\Controllers\Gestor\BenelisController::class, 'encuesta'])->name('beneficiarios.encuesta');
});
Route::prefix('gestor/beneficiarios')->middleware(['auth', 'check.page.active'])->group(function () {
    Route::get('/', [App\Http\Controllers\Gestor\BenelisController::class, 'index'])->name('beneficiarios.index');
    Route::get('/encuesta/{id}', [App\Http\Controllers\Gestor\BenelisController::class, 'encuesta'])->name('beneficiarios.encuesta');
    Route::post('/encuesta/{id}/guardar', [App\Http\Controllers\Gestor\BenelisController::class, 'guardarEncuesta'])->name('beneficiarios.guardarEncuesta');
});
Route::get('/gestor/documentos', [App\Http\Controllers\Gestor\DocumentosController::class, 'index'])->name('documentos.index')->middleware(['auth', 'check.page.active']);
Route::get('/gestor/documentos/exportar/{tipo}', [App\Http\Controllers\Gestor\DocumentosController::class, 'exportar'])->name('documentos.exportar')->middleware(['auth', 'check.page.active']);
    Route::get('/gestor/documentos/exportar/{tipo}', [App\Http\Controllers\Gestor\DocumentosController::class, 'exportar'])->name('documentos.exportar');



    Route::get('resumen-general', [ResumenController::class, 'index'])->name('resumen.index');
use App\Http\Controllers\Gestor\ResumenController;

Route::get('gestor/dashboard', [ResumenController::class, 'index'])->name('resumen.index')->middleware(['auth', 'check.page.active']);


Route::prefix('gestor')->middleware(['auth', 'check.page.active'])->group(function () {
    Route::get('/citas', [CitasController::class, 'index'])->name('gestor.citas.index');
});
Route::get('/gestor/citas', [CitasController::class, 'index'])->name('gestor.citas.index')->middleware(['auth', 'check.page.active']);



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
Route::middleware(['auth', 'check.page.active'])->get('/calendario', function () {
    return view('pages.calendar');
});

Route::get('/mapa', [TuControlador::class, 'mostrarMapa'])->name('mapa');

//mensajeria 
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index')->middleware(['auth', 'check.page.active']);
Route::post('/chat/mensaje', [ChatController::class, 'store'])->name('chat.store')->middleware(['auth', 'check.page.active']);
Route::delete('/chat/mensaje/{id}', [ChatController::class, 'destroy'])->name('chat.destroy')->middleware(['auth', 'check.page.active']);

// perfil
Route::get('perfil/edit', [PerfilController::class, 'edit'])->name('perfil.edit')->middleware(['auth', 'check.page.active']);
Route::post('perfil/update', [PerfilController::class, 'update'])->name('perfil.update')->middleware(['auth', 'check.page.active']);
Route::delete('perfil/remove-photo', [PerfilController::class, 'removePhoto'])->name('perfil.remove.photo')->middleware(['auth', 'check.page.active']);

// ====================================
// RUTAS SOLO PARA ADMINISTRADOR
// ====================================
Route::middleware(['auth', 'adminonly'])->prefix('admin')->group(function () {

    // 👥 Gestión de Usuarios
    Route::get('/usuarios', [App\Http\Controllers\Admin\UsuarioController::class, 'index'])->name('admin.usuarios');

    // 🔐 Roles y permisos
    Route::get('/roles', [App\Http\Controllers\Admin\RolController::class, 'index'])->name('admin.roles');
    Route::post('/roles/asignar', [App\Http\Controllers\Admin\RolController::class, 'asignar'])->name('admin.roles.asignar');

    // 📊 Reportes de avances
    Route::get('/reportes/avances', [App\Http\Controllers\Admin\ReporteController::class, 'avances'])->name('admin.reportes.avances');
    Route::get('/reportes/financieros', [App\Http\Controllers\Admin\ReporteController::class, 'financieros'])->name('admin.reportes.financieros');
    Route::get('/reportes/comunidades', [App\Http\Controllers\Admin\ReporteController::class, 'comunidades'])->name('admin.reportes.comunidades');
    




    // 📅 Citas
    Route::get('/citas', [App\Http\Controllers\Admin\CitaController::class, 'index'])->name('admin.citas');

    // 🏘️ Comunidades
    Route::get('/comunidades', [App\Http\Controllers\Admin\ComunidadController::class, 'index'])->name('admin.comunidades');

    // ⚙️ Configuración del sistema
    Route::get('/configuraciones', [App\Http\Controllers\Admin\ConfigController::class, 'index'])->name('admin.configuraciones');
});

Route::get('notificaciones', [App\Http\Controllers\NotificacionController::class, 'index'])
    ->name('notificaciones.index')
    ->middleware(['auth', 'check.page.active']);

Route::delete('notificaciones/{id}/eliminar', [App\Http\Controllers\NotificacionController::class, 'eliminar'])
    ->name('notificaciones.eliminar')
    ->middleware(['auth', 'check.page.active']);

Route::delete('notificaciones/eliminar-todas', [App\Http\Controllers\NotificacionController::class, 'eliminarTodas'])
    ->name('notificaciones.eliminar-todas')
    ->middleware(['auth', 'check.page.active']);

//Route::get('gestor/asignados', [App\Http\Controllers\Gestor\ProyectoController::class, 'asignados'])->name('gestor.asignados')->middleware('auth');
//Route::post('gestor/asignados/{proyecto}/evidencia', [App\Http\Controllers\Gestor\ProyectoController::class, 'subirEvidencia'])->name('gestor.evidencia.subir')->middleware('auth');
//Route::get('gestor/asignados/{proyecto}/evidencia/{archivo}', [App\Http\Controllers\Gestor\ProyectoController::class, 'descargarEvidencia'])->name('gestor.evidencia.descargar')->middleware('auth');
//Route::delete('gestor/asignados/{proyecto}/evidencia/{archivo}', [App\Http\Controllers\Gestor\ProyectoController::class, 'eliminarEvidencia'])->name('gestor.evidencia.eliminar')->middleware('auth');


use Illuminate\Support\Facades\Password;
use App\Models\User;

Route::get('/test-email', function () {
    $user = User::where('email', 'nery.varela@gmail.com')->first();

    if (!$user) {
        return '❌ Usuario no encontrado.';
    }

    // Generar token y enviar correo manualmente
    $token = Password::createToken($user);
    $user->sendPasswordResetNotification($token);

    return '✅ Correo de recuperación enviado correctamente.';
});

Route::get('admin/configuracion', function () {
    return view('admin.configuracion');
})->middleware(['auth']);

Route::get('gestor/configuracion', function () {
    return view('gestor.configuracion');
})->middleware(['auth', 'check.page.active']);

use App\Http\Controllers\Gestor\AdminConfiguracionController;

Route::get('admin/configuracion/activar-desactivar', [AdminConfiguracionController::class, 'activarDesactivar'])->middleware(['auth']);
Route::post('admin/configuracion/activar-desactivar', [AdminConfiguracionController::class, 'toggle'])->middleware(['auth']);

use App\Http\Controllers\Gestor\ConfiguracionController;



//use App\Http\Controllers\Gestor\BeneficiarioController;

// Grupo principal con middleware auth
Route::middleware(['auth'])->group(function () {
    
    // Grupo prefijado con 'beneficiarios'
    Route::prefix('beneficiarios')->group(function () {
        Route::get('/', [BeneficiarioController::class, 'index'])->name('beneficiarios.index');
        Route::get('/formulario/{proyecto_id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.formulario');
        Route::post('/guardar', [BeneficiarioController::class, 'guardar'])->name('beneficiarios.store');

        // Rutas para selects dependientes (internas del sistema)
        Route::get('/municipios/{departamento_id}', [BeneficiarioController::class, 'obtenerMunicipios'])->name('beneficiarios.municipios');
        Route::get('/colonias/{municipio_id}', [BeneficiarioController::class, 'obtenerColonias'])->name('beneficiarios.colonias');
    });

    // Rutas API externas
    Route::get('/api/municipios/{departamentoId}', [BeneficiarioController::class, 'obtenerMunicipios']);
    Route::get('/api/colonias/{municipioId}', [BeneficiarioController::class, 'obtenerColonias']);
});


Route::get('/beneficiarios/registrar/{proyecto_id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.formulario')->middleware(['auth', 'check.page.active']);
Route::post('/beneficiarios', [BeneficiarioController::class, 'guardar'])->name('beneficiarios.store')->middleware(['auth', 'check.page.active']);
Route::get('/beneficiarios', [BeneficiarioController::class, 'index'])->name('beneficiarios.index')->middleware(['auth', 'check.page.active']);
Route::get('/beneficiarios/formulario/{id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.formulario')->middleware(['auth', 'check.page.active']);
Route::post('/beneficiarios', [BeneficiarioController::class, 'store'])->name('beneficiarios.store')->middleware(['auth', 'check.page.active']);


Route::get('/beneficiarios/municipios/{departamento_id}', [BeneficiarioController::class, 'obtenerMunicipios'])->name('beneficiarios.municipios');
Route::get('/beneficiarios/colonias/{municipio_id}', [BeneficiarioController::class, 'obtenerColonias'])->name('beneficiarios.colonias');

// RUTAS PARA GESTIÓN DE BENEFICIARIOS DEL GESTOR
Route::prefix('gestor/beneficiarios')->middleware(['auth', 'check.page.active'])->group(function () {
    // Lista de beneficiarios por proyecto
    Route::get('/lista', [BenelisController::class, 'lista'])->name('beneficiarios.lista');

    // Selección de proyecto para registrar
    Route::get('/proyectos', [BenelisController::class, 'seleccionarProyecto'])->name('beneficiarios.proyectos');

    // Formulario para registrar beneficiario en proyecto
    Route::get('/formulario/{proyecto_id}', [BenelisController::class, 'formulario'])->name('beneficiarios.formulario');

    // Guardar beneficiario
    Route::post('/guardar', [BenelisController::class, 'guardar'])->name('beneficiarios.guardar');

    // Encuesta a beneficiario
    Route::get('/encuesta/{id}', [BenelisController::class, 'encuesta'])->name('beneficiarios.encuesta');
    Route::post('/encuesta/{id}/guardar', [BenelisController::class, 'guardarEncuesta'])->name('beneficiarios.guardarEncuesta');

    // AJAX: Municipios y colonias
    Route::get('/municipios/{departamento_id}', [BenelisController::class, 'obtenerMunicipios'])->name('beneficiarios.municipios');
    Route::get('/colonias/{municipio_id}', [BenelisController::class, 'obtenerColonias'])->name('beneficiarios.colonias');

    Route::get('/encuesta/{id}', [BenelisController::class, 'encuesta'])->name('beneficiarios.encuesta');
Route::post('/encuesta/{id}/guardar', [BenelisController::class, 'guardarEncuesta'])->name('beneficiarios.guardarEncuesta');
Route::get('/encuesta/{id}', [BenelisController::class, 'encuesta'])->name('beneficiarios.encuesta');
Route::post('/encuesta/{id}/guardar', [BenelisController::class, 'guardarEncuesta'])->name('beneficiarios.guardarEncuesta');
Route::get('documentacion', [App\Http\Controllers\Gestor\BenelisController::class, 'evidenciaYDocumentacion'])->name('documentacion.index');
Route::get('/documentacion', [App\Http\Controllers\Gestor\BenelisController::class, 'evidenciaYDocumentacion'])->name('documentacion.index');
Route::get('/gestor/asignados', [ProyectoController::class, 'asignados'])->name('gestor.asignados');
Route::get('/gestor/proyectos/exportar-excel/{id}', [\App\Http\Controllers\Gestor\ProyectoController::class, 'exportarExcel'])->name('gestor.proyectos.exportarExcel');
Route::get('/gestor/proyectos/exportar-word/{id}', [\App\Http\Controllers\Gestor\ProyectoController::class, 'exportarWord'])->name('gestor.proyectos.exportarWord');

});


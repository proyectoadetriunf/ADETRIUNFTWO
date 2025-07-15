<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
//use App\Http\Controllers\BeneficiarioController;
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

Route::prefix('gestor/proyectos')->group(function () {
    Route::get('/', [ProyectoController::class, 'index'])->name('gestor.proyectos.index');
    Route::post('/store', [ProyectoController::class, 'store'])->name('gestor.proyectos.store');
    Route::post('/asignar', [ProyectoController::class, 'asignar'])->name('gestor.proyectos.asignar');

    // ✅ NUEVAS RUTAS PARA EDITAR Y ELIMINAR
    Route::get('/editar/{id}', [ProyectoController::class, 'editar'])->name('gestor.proyectos.editar');
    Route::put('/actualizar/{id}', [ProyectoController::class, 'actualizar'])->name('gestor.proyectos.actualizar');
    Route::delete('/eliminar/{id}', [ProyectoController::class, 'eliminar'])->name('gestor.proyectos.eliminar');
});
Route::get('gestor/proyectos/editar/{id}', [ProyectoController::class, 'editar'])->name('gestor.proyectos.editar');
Route::put('gestor/proyectos/actualizar/{id}', [ProyectoController::class, 'actualizar'])->name('gestor.proyectos.actualizar');
Route::delete('gestor/proyectos/eliminar/{id}', [ProyectoController::class, 'eliminar'])->name('gestor.proyectos.eliminar');
Route::post('gestor/proyectos/asignar', [ProyectoController::class, 'asignar'])->name('gestor.proyectos.asignar');
Route::put('gestor/proyectos/{id}/actualizar', [ProyectoController::class, 'actualizar'])->name('gestor.proyectos.actualizar');
use App\Http\Controllers\Gestor\CronogramaController;

Route::prefix('gestor/proyectos')->middleware(['auth'])->group(function () {
    Route::get('cronograma/{proyecto_id}', [CronogramaController::class, 'index'])->name('gestor.proyectos.cronograma');
    Route::post('cronograma/store', [CronogramaController::class, 'store'])->name('gestor.proyectos.cronograma.store');
    Route::post('cronograma/finalizar/{actividad_id}', [CronogramaController::class, 'finalizar'])->name('gestor.proyectos.cronograma.finalizar');
});
Route::get('gestor/asignados', [ProyectoController::class, 'asignados'])->name('gestor.asignados');
Route::get('gestor/asignados/cronograma/{id}', [ProyectoController::class, 'cronograma'])->name('gestor.cronograma');
Route::get('gestor/asignados/avances/{id}', [ProyectoController::class, 'avances'])->name('gestor.avances');
Route::get('gestor/asignados', [ProyectoController::class, 'asignados'])->name('gestor.proyectos.asignados');
Route::get('gestor/asignados/{id}/cronograma', [ProyectoController::class, 'cronograma'])->name('gestor.proyectos.cronograma');
Route::get('gestor/asignados/{id}/avances', [ProyectoController::class, 'avances'])->name('gestor.proyectos.avances');
Route::get('gestor/asignados/cronograma/{id}', [ProyectoController::class, 'cronograma'])->name('gestor.proyectos.cronograma');
Route::post('gestor/proyectos/cronograma/finalizar/{id}', [ProyectoController::class, 'finalizarActividad'])->name('gestor.proyectos.cronograma.finalizar');
Route::post('/gestor/proyectos/avances/guardar', [ProyectoController::class, 'guardarAvance'])
     ->name('gestor.proyectos.avances.guardar');

Route::post('gestor/proyectos/avances/store', [ProyectoController::class, 'guardarAvance'])->name('gestor.proyectos.avances.store');

Route::post('gestor/proyectos/avances/guardar', [ProyectoController::class, 'guardarAvance'])->name('gestor.proyectos.avances.store');
Route::post('/gestor/proyectos/avances/guardar', [ProyectoController::class, 'guardarAvance'])
    ->name('gestor.proyectos.avances.store');
Route::get('gestor/proyectos/avances/{id}', [ProyectoController::class, 'avances'])->name('gestor.proyectos.avances');
Route::post('/gestor/proyectos/avances/guardar', [App\Http\Controllers\Gestor\ProyectoController::class, 'guardarAvance'])
    ->name('gestor.proyectos.avances.guardar');
Route::get('gestor/asignados/cronograma/{id}', [App\Http\Controllers\Gestor\ProyectoController::class, 'cronograma'])
    ->name('gestor.cronograma');
Route::post('/gestor/proyectos/progreso/{id}', [App\Http\Controllers\Gestor\ProyectoController::class, 'actualizarProgreso'])
    ->name('gestor.proyectos.actualizarProgreso');

Route::get('/gestor/proyectos/avances/exportar-word/{id}', [ProyectoController::class, 'exportarWord'])->name('gestor.proyectos.exportarWord');
Route::get('/gestor/proyectos/avances/exportar-excel/{id}', [ProyectoController::class, 'exportarExcel'])->name('gestor.proyectos.exportarExcel');


Route::get('/beneficiarios', [\App\Http\Controllers\Gestor\BenelisController::class, 'index'])->name('beneficiarios.index');
Route::get('/beneficiarios', [BenelisController::class, 'index'])->name('beneficiarios.index');
Route::get('/beneficiarios', [BenelisController::class, 'index'])->name('beneficiarios.index');
use App\Http\Controllers\Gestor\BenelisController;

Route::get('/beneficiarios', [BenelisController::class, 'index'])->name('beneficiarios.index');
Route::get('/beneficiarios/proyectos-asignados', [App\Http\Controllers\Gestor\BenelisController::class, 'proyectosAsignados'])
    ->name('beneficiarios.proyectos.asignados');
Route::get('/beneficiarios/seleccionar-proyecto', [BenelisController::class, 'seleccionarProyecto'])->name('beneficiarios.seleccionar');
Route::get('/beneficiarios/registrar/{proyecto_id}', [BenelisController::class, 'formulario'])->name('beneficiarios.registrar');
Route::get('beneficiarios/seleccionar', [BenelisController::class, 'seleccionarProyecto'])->name('beneficiarios.seleccionar');
Route::get('beneficiarios/formulario/{proyecto_id}', [BenelisController::class, 'formulario'])->name('beneficiarios.formulario');

// Mostrar proyectos asignados para registrar beneficiario
Route::get('beneficiarios/seleccionar-proyecto', [BenelisController::class, 'seleccionarProyecto'])->name('beneficiarios.seleccionar');

// Mostrar formulario de registro con el proyecto ya seleccionado
Route::get('beneficiarios/formulario/{proyecto_id}', [BenelisController::class, 'formulario'])->name('beneficiarios.formulario');

// Guardar beneficiario (esto ya lo debes tener, solo por si acaso)
Route::post('beneficiarios/store', [BenelisController::class, 'store'])->name('beneficiarios.store');
Route::get('beneficiarios/proyectos-asignados', [BenelisController::class, 'proyectosAsignados'])->name('beneficiarios.proyectos.asignados');

/***********************************************************************/
Route::get('/beneficiarios', [App\Http\Controllers\Gestor\BenelisController::class, 'mostrarProyectosAsignados'])->name('beneficiarios.index');
Route::post('/beneficiarios/guardar', [BeneficiarioController::class, 'store'])->name('beneficiarios.guardar');
Route::get('/beneficiarios', [BeneficiarioController::class, 'index'])->name('beneficiarios.index');
Route::get('/beneficiarios/formulario/{proyecto_id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.formulario');
Route::post('/beneficiarios/guardar', [BeneficiarioController::class, 'guardar'])->name('beneficiarios.guardar');
// AJAX para cargar municipios y colonias
Route::get('/beneficiarios/municipios/{id}', [BeneficiarioController::class, 'obtenerMunicipios']);
Route::get('/beneficiarios/colonias/{id}', [BeneficiarioController::class, 'obtenerColonias']);
Route::get('/beneficiarios/registrar/{proyecto_id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.registrar');
Route::get('gestor/beneficiarios/formulario/{proyecto_id}', [BenelisController::class, 'formulario'])->name('gestor.beneficiarios.formulario');
Route::get('/beneficiarios/formulario/{proyecto_id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.formulario');
Route::post('/beneficiarios/guardar', [BeneficiarioController::class, 'store'])->name('beneficiarios.store');
Route::get('/beneficiarios/municipios/{id}', [BeneficiarioController::class, 'obtenerMunicipios']);
Route::get('/beneficiarios/colonias/{id}', [BeneficiarioController::class, 'obtenerColonias']);
Route::prefix('gestor/beneficiarios')->middleware('auth')->group(function () {
    Route::get('seleccionar', [BenelisController::class, 'seleccionarProyecto'])->name('gestor.beneficiarios.seleccionarProyecto');
    Route::get('formulario/{id}', [BenelisController::class, 'formulario'])->name('gestor.beneficiarios.formulario');
});
Route::get('gestor/beneficiarios/formulario/{proyecto_id}', [BenelisController::class, 'formulario'])->name('gestor.beneficiarios.formulario');

Route::prefix('beneficiarios')->middleware(['auth'])->group(function () {
    Route::get('/', [BeneficiarioController::class, 'index'])->name('beneficiarios.index');
    Route::get('/formulario/{proyecto_id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.formulario');
    Route::post('/guardar', [BeneficiarioController::class, 'guardar'])->name('beneficiarios.guardar');
});

Route::get('/beneficiarios', [BenelisController::class, 'index'])->name('beneficiarios.index');
Route::get('/beneficiarios/proyectos-asignados', [BenelisController::class, 'proyectosAsignados'])->name('beneficiarios.proyectos.asignados');
/**********************************Salon******************************** */
// Rutas de gestión del salón
Route::prefix('gestor/salon')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\SalonController::class, 'index'])->name('gestor.salon.index');
    Route::post('/guardar', [App\Http\Controllers\SalonController::class, 'guardar'])->name('gestor.salon.guardar');
});




/*********************************************************************** */
/**********************************solicitudes******************************** */
// Rutas de gestión del salón
Route::get('crear/solicitud', [SolicitudController::class, 'create'])->name('solicitudes.create');
Route::post('crear/solicitud', [SolicitudController::class, 'store'])->name('solicitudes.store');
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
    ->name('notificaciones')
    ->middleware('auth');

Route::get('gestor/asignados', [App\Http\Controllers\Gestor\ProyectoController::class, 'asignados'])->name('gestor.asignados')->middleware('auth');
Route::post('gestor/asignados/{proyecto}/evidencia', [App\Http\Controllers\Gestor\ProyectoController::class, 'subirEvidencia'])->name('gestor.evidencia.subir')->middleware('auth');
Route::get('gestor/asignados/{proyecto}/evidencia/{archivo}', [App\Http\Controllers\Gestor\ProyectoController::class, 'descargarEvidencia'])->name('gestor.evidencia.descargar')->middleware('auth');
Route::delete('gestor/asignados/{proyecto}/evidencia/{archivo}', [App\Http\Controllers\Gestor\ProyectoController::class, 'eliminarEvidencia'])->name('gestor.evidencia.eliminar')->middleware('auth');


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

use App\Http\Controllers\Gestor\BeneficiarioController;

Route::prefix('beneficiarios')->middleware(['auth'])->group(function () {
    Route::get('/', [BeneficiarioController::class, 'index'])->name('beneficiarios.index');
    Route::get('/formulario/{proyecto_id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.formulario');
    Route::post('/guardar', [BeneficiarioController::class, 'guardar'])->name('beneficiarios.guardar');

    // Para selects dependientes si los usas
    Route::get('/municipios/{departamento_id}', [BeneficiarioController::class, 'obtenerMunicipios'])->name('beneficiarios.municipios');
    Route::get('/colonias/{municipio_id}', [BeneficiarioController::class, 'obtenerColonias'])->name('beneficiarios.colonias');
});

Route::prefix('beneficiarios')->middleware(['auth'])->group(function () {
    Route::get('/formulario/{proyecto_id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.formulario');
});

Route::prefix('beneficiarios')->middleware('auth')->group(function () {
    Route::get('/', [BeneficiarioController::class, 'index'])->name('beneficiarios.index');
    Route::get('/formulario/{proyecto_id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.formulario');
    Route::post('/guardar', [BeneficiarioController::class, 'guardar'])->name('beneficiarios.guardar');
});
Route::get('/beneficiarios/municipios/{departamento}', [BeneficiarioController::class, 'obtenerMunicipios'])->name('beneficiarios.municipios');
Route::get('/beneficiarios/colonias/{municipio}', [BeneficiarioController::class, 'obtenerColonias'])->name('beneficiarios.colonias');
Route::get('/beneficiarios/formulario/{proyecto_id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.formulario');
Route::get('/beneficiarios/formulario/{id}', [BeneficiarioController::class, 'formulario']);
Route::post('/beneficiarios', [BeneficiarioController::class, 'store'])->name('beneficiarios.store');
// Beneficiarios
Route::get('/beneficiarios', [BeneficiarioController::class, 'index'])->name('beneficiarios.index');
Route::get('/beneficiarios/formulario/{proyecto_id}', [BeneficiarioController::class, 'formulario']);
Route::post('/beneficiarios', [BeneficiarioController::class, 'guardar'])->name('beneficiarios.store');

// API para selects dependientes
Route::get('/api/municipios/{departamento_id}', [BeneficiarioController::class, 'obtenerMunicipios']);
Route::get('/api/colonias/{municipio_id}', [BeneficiarioController::class, 'obtenerColonias']);

Route::get('/beneficiarios/formulario/{proyecto_id}', [BeneficiarioController::class, 'formulario']);

Route::prefix('beneficiarios')->group(function () {
    Route::get('/', [BeneficiarioController::class, 'index'])->name('beneficiarios.index');
    Route::get('/formulario/{id}', [BeneficiarioController::class, 'formulario'])->name('beneficiarios.formulario');
    Route::post('/guardar', [BeneficiarioController::class, 'guardar'])->name('beneficiarios.store');
});

Route::get('/api/municipios/{departamentoId}', [BeneficiarioController::class, 'obtenerMunicipios']);
Route::get('/api/colonias/{municipioId}', [BeneficiarioController::class, 'obtenerColonias']);

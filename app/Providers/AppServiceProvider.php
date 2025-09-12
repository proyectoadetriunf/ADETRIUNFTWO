<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Notifications\Notification;
use App\Models\DatabaseNotification;
use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if (app()->environment('production')) {
        URL::forceScheme('https');
    }
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        $this->app['events']->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $user = Auth::user();

            if (!$user) {
                return;
            }

            $rol = strtolower(trim($user->rol_id));
/*************************************menu de admin***************************** */
            if ($rol === 'admin') {
                // Menú de ADMIN
               /* $event->menu->add([
                    'text' => 'Dashboard',
                    'icon' => 'fas fa-home',
                    'url'  => 'admin/dashboard',
                ]);
               */
                $event->menu->add([
                    'text' => 'Citas Programadas',
                    'icon' => 'fas fa-calendar-alt',
                    'url'  => 'gestor/citas',
                ]);

                $event->menu->add([
                    'text' => 'Control de Reuniones',
                    'icon' => 'fas fa-door-closed',
                    'url'  => 'gestor/salon',
                ]);

                $event->menu->add([
                    'text' => 'Otorgar Permisos',
                    'icon' => 'fas fa-user-cog',
                    'submenu' => [
                        [
                            'text' => 'Usuarios',
                            'icon' => 'fas fa-users',
                            'url'  => 'admin/usuarios',
                        ],
                        [
                            'text' => 'Roles y Permisos',
                            'icon' => 'fas fa-user-shield',
                            'url'  => 'admin/roles',
                        ],
                    ],
                ]);

                $event->menu->add([
                    'text' => 'Gestionar Proyectos',
                    'icon' => 'fas fa-folder-plus',
                    'submenu' => [
                        [
                            'text' => 'Crear y Asignar',
                            'icon' => 'fas fa-folder',
                            'url'  => 'gestor/proyectos',
                        ],
                        [
                            'text' => 'Avances de Proyectos',
                            'icon' => 'fas fa-tasks',
                            'url'  => 'mostrar/avance',
                            'id'   => 'abrirModalAvance',
                        ],
                        [
                            'text' => 'documentacion de proyectos',
                            'icon' => 'fas fa-upload',
                            'url'  => 'documentos/admin',
                            'id'   => 'abrirModalDocumentacion',
                        ],
                        [
                            'text' => 'Solicitudes',
                            'icon' => 'fas fa-paper-plane',
                            'url'  => 'mostrar/solicitudes',
                            'id'   => 'abrirModalSolicitud',
                        ],
                    ],
                ]);

                $event->menu->add([
                    'text' => 'Técnico de Proyecto',
                    'icon' => 'fas fa-briefcase',
                    'submenu' => [
                        [
                            'text' => 'Registrar beneficiario',
                            'icon' => 'fas fa-user-plus',
                            'url'  => 'beneficiarios',
                        ],
                        [
                            'text' => 'Asignar Actividades / Tareas',
                            'icon' => 'fas fa-tasks',
                            'url'  => 'admin/tareas',
                        ],
                        [
                            'text' => 'lista de Beneficiarios',
                            'icon' => 'fas fa-users',
                            'url'  => 'gestor/beneficiarios',
                        ],
                       /* [
                            'text' => 'Evidencias y Documentos',
                            'icon' => 'fas fa-folder-open',
                            'url'  => 'gestor/documentos',
                        ],*/
                        [
                            'text' => 'Resumen General',
                            'icon' => 'fas fa-chart-bar',
                            'url'  => 'gestor/dashboard',
                        ],
                        [
                            'text' => 'Resumen',
                            'icon' => 'fas fa-chart-line',
                            'url'  => 'gestor/comuni',
                        ],
                    ],
                ]);

                $event->menu->add([
                    'text' => 'Reportes',
                    'icon' => 'fas fa-chart-line',
                    'submenu' => [
                        [
                            'text' => 'Avances por Proyecto',
                            'icon' => 'fas fa-project-diagram',
                            'url'  => 'admin/reportes/avances',
                        ],
                        [
                            'text' => 'Financieros',
                            'icon' => 'fas fa-dollar-sign',
                            'url'  => 'admin/reportes/financieros',
                        ],
                        [
                            'text' => 'Inversión por Comunidad',
                            'icon' => 'fas fa-map-marked-alt',
                            'url'  => 'admin/reportes/comunidades',
                        ],
                    ],
                ]);
            }
/*******************************************menu de gestor************************************************ */
            if ($rol === 'moderador') {
                // Menú de MODERADOR
               
                

                $event->menu->add([
                    'text' => 'Citas Programadas',
                    'icon' => 'fas fa-calendar-alt',
                    'url'  => 'gestor/citas',
                ]);

                $event->menu->add([
                    'text' => 'Control de Reuniones',
                    'icon' => 'fas fa-door-closed',
                    'url'  => 'gestor/salon',
                ]);
                $event->menu->add([
                    'text' => 'crear solicitudes',
                            'icon' => 'fas fa-paper-plane',
                            'url'  => 'crear/solicitud',
                ]);
                $event->menu->add([
                    'text' => 'proyectos asiganado',
                    'icon' => 'fas fa-folder',
                    'url'  => 'gestor/asignados',
                ]);

                $event->menu->add([
                    'text' => 'Registrar beneficiario',
                    'icon' => 'fas fa-user-plus',
                    'url'  => 'beneficiarios',
                ]);

                $event->menu->add([
                    'text' => 'Actividades/Tareas Asignadas',
                    'icon' => 'fas fa-tasks',
                    'url'  => 'gestor/tareas',
                ]);

                $event->menu->add([
                    'text' => 'Beneficiarios',
                    'icon' => 'fas fa-users',
                    'url'  => 'gestor/beneficiarios',
                ]);

               /* $event->menu->add([
                    'text' => 'Evidencias y Documentos',
                    'icon' => 'fas fa-folder-open',
                    'url'  => 'gestor/documentos',
                ]);*/

                $event->menu->add([
                    'text' => 'Resumen General',
                    'icon' => 'fas fa-chart-bar',
                    'url'  => 'gestor/dashboard',
                ]);
               
                
                
            }

            // TOPNAV
            $notificacionesNoLeidas = \App\Models\NotificacionPersonalizada::where('user_id', new \MongoDB\BSON\ObjectId($user->_id))
                ->where('leida', false)
                ->count();
            $event->menu->add([
             'type'          => 'navbar-notification',
             'id'            => 'notificaciones',
             'icon'          => 'fas fa-bell',
             'url'           => 'notificaciones', // Ruta a tu página de notificaciones
             'topnav_right'  => true,
             'label'         => $notificacionesNoLeidas > 0 ? $notificacionesNoLeidas : null, // Muestra el contador
             'dropdown_mode' => false, // No mostrar el menú desplegable
            ]);


            $event->menu->add([
                'type'         => 'navbar-item',
                'text'         => '',
                'icon'         => 'fas fa-user',
                'url'          => 'perfil/edit',
                'topnav_right' => true,
            ]);

            $event->menu->add([
                'type'         => 'navbar-item',
                'text'         => '',
                'icon'         => 'fas fa-cog',
                'url'          => $rol === 'admin' ? 'admin/configuracion' : ($rol === 'moderador' ? 'gestor/configuracion' : 'configuracion'),
                'topnav_right' => true,
            ]);
        });
    }
}

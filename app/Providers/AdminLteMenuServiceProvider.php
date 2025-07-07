<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AdminLteMenuServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Definimos el menú dinámico según el rol del usuario autenticado
        $user = Auth::user();

        if (!$user) {
            // No autenticado: menú vacío
            config(['adminlte.menu' => []]);
            return;
        }

        if ($user->rol === 'admin') {
            config(['adminlte.menu' => $this->menuAdmin()]);
            // Cambiar dashboard url si quieres
            config(['adminlte.dashboard_url' => 'admin/dashboard']);
        } else if (in_array($user->rol, ['tecnico', 'moderador'])) {
            config(['adminlte.menu' => $this->menuGestor()]);
            config(['adminlte.dashboard_url' => 'gestor/dashboard']);
        } else {
            // Usuario con rol no contemplado, menú vacío o personalizado
            config(['adminlte.menu' => []]);
            config(['adminlte.dashboard_url' => 'home']);
        }
    }

    private function menuAdmin()
    {
        return [
            // Aquí copias el menú de admin que ya tienes en tu config/adminlte.php
            [
                'type' => 'sidebar-menu-search',
                'text' => 'Buscar...',
            ],
            [
    'text' => 'Reservas de Salón',
    'icon' => 'fas fa-door-closed',
    'url'  => 'gestor/salon',
],

           
           /* [
                'text' => 'Calendario',
                'icon' => 'fas fa-calendar-alt',
                'url'  => 'calendario',
            ],
            */
        
           
            [
          
                    'text' => 'Citas Programadas',
                    'icon' => 'fas fa-calendar-alt',
                    'url'  => 'gestor/citas',
                
            ],
            [
                'text' => 'Comunidades',
                'icon' => 'fas fa-map',
                'url'  => 'admin/comunidades',
            ],
            [
                'text' => 'Gestionar proyectos',
                'icon' => 'fas fa-folder-plus',
                'submenu' => [
                    [
                        'text' => 'Registrar beneficiario',
                        'icon' => 'fas fa-user-plus',
                        'url'  => 'beneficiarios',
                    ],
                    [
                        'text' => 'Registrar avance',
                        'icon' => 'fas fa-tasks',
                        'url'  => '#',
                        'id'   => 'abrirModalAvance',
                    ],
                    [
                        'text' => 'Subir documentación',
                        'icon' => 'fas fa-upload',
                        'url'  => '#',
                        'id'   => 'abrirModalDocumentacion',
                    ],
                    [
                        'text' => 'Crear solicitud',
                        'icon' => 'fas fa-paper-plane',
                        'url'  => '#',
                        'id'   => 'abrirModalSolicitud',
                    ],
                ],
                [
                'text' => 'Administración',
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
            ],
             [
                'text' => 'Gestionar proyectos',
                'icon' => 'fas fa-folder-plus',
                'submenu' => [
                    [
                        'text' => 'Registrar beneficiario',
                        'icon' => 'fas fa-user-plus',
                        'url'  => 'beneficiarios',
                    ],
                    [
                        'text' => 'Registrar avance',
                        'icon' => 'fas fa-tasks',
                        'url'  => '#',
                        'id'   => 'abrirModalAvance',
                    ],
                    [
                        'text' => 'Subir documentación',
                        'icon' => 'fas fa-upload',
                        'url'  => '#',
                        'id'   => 'abrirModalDocumentacion',
                    ],
                    [
                        'text' => 'Crear solicitud',
                        'icon' => 'fas fa-paper-plane',
                        'url'  => '#',
                        'id'   => 'abrirModalSolicitud',
                    ],
                ],
            ],
            ],
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
         

            
            // Notificaciones (topnav)
            [
                'type'            => 'navbar-notification',
                'id'              => 'notificaciones',
                'icon'            => 'fas fa-bell',
                'label'           => 3,
                'label_color'     => 'danger',
                'url'             => '#',
                'topnav_right'    => true,
                'dropdown_mode'   => true,
                //'dropdown_flabel' => 'Ver todas las notificaciones',
                'dropdown_items'  => [
                    [
                        'text' => 'Proyecto “Salud Rural” necesita evidencia',
                        'url'  => '#',
                    ],
                    [
                        'text' => '2 documentos faltantes en “Mujeres al Futuro”',
                        'url'  => '#',
                    ],
                    [
                        'text' => '5 solicitudes sin revisar',
                        'url'  => '#',
                    ],
                ],
            ],
            // Perfil (topnav)
            [
                'type'         => 'navbar-item',
                'text'         => '',
                'icon'         => 'fas fa-user',
                'url'          => 'perfil/edit',
                'topnav_right' => true,
            ],
            // Configuración (topnav)
            [
                'type'         => 'navbar-item',
                'text'         => '',
                'icon'         => 'fas fa-cog',
                'url'          => 'configuracion',
                'topnav_right' => true,
            ],
            // Buscador topnav
            [
                'type'         => 'navbar-search',
                'text'         => 'Buscar...',
                'topnav_right' => true,
            ],
        
            
        ];
    }
    
/******************************************************************menu gestor******************************************* */
    private function menuGestor()
    {
        return [
            // Aquí copias el menú de gestor/técnico/moderador que ya tienes en config/adminlte.php
            [
                'type' => 'sidebar-menu-search',
                'text' => 'Buscar...',
            ],
            [
    'text' => 'Reservas de Salón',
    'icon' => 'fas fa-door-closed',
    'url'  => 'gestor/salon',
],

            [
                'text' => '👷 Técnico/Gestor de Proyecto',
                'icon' => 'fas fa-user-cog',
                'submenu' => [
                    [
                        'text' => '🗂️ Gestión de Proyectos',
                        'url'  => 'gestor/proyectos',
                    ],
                    [
                        'text' => '📋 Actividades / Tareas',
                        'url'  => 'gestor/tareas',
                    ],
                    [
                        'text' => '📅 Citas Programadas',
                        'url'  => 'gestor/citas',
                    ],
                    [
                        'text' => '👥 Beneficiarios',
                        'url'  => 'gestor/beneficiarios',
                    ],
                    [
                        'text' => '📂 Evidencias y Documentos',
                        'url'  => 'gestor/documentos',
                    ],
                    [
                        'text' => '📊 Resumen General',
                        'url'  => 'gestor/dashboard',
                    ],
                    [
                        'text' => '📊 Resumen',
                        'url'  => 'gestor/comuni',
                    ],
                ],
            ],
            // Notificaciones (topnav)
            [
                'type'            => 'navbar-notification',
                'id'              => 'notificaciones',
                'icon'            => 'fas fa-bell',
                'label'           => 3,
                'label_color'     => 'danger',
                'url'             => '#',
                'topnav_right'    => true,
                'dropdown_mode'   => true,
                //'dropdown_flabel' => 'Ver todas las notificaciones',
                'dropdown_items'  => [
                    [
                        'text' => 'Proyecto “Salud Rural” necesita evidencia',
                        'url'  => '#',
                    ],
                    [
                        'text' => '2 documentos faltantes en “Mujeres al Futuro”',
                        'url'  => '#',
                    ],
                    [
                        'text' => '5 solicitudes sin revisar',
                        'url'  => '#',
                    ],
                ],
            ],
            // Perfil (topnav)
            [
                'type'         => 'navbar-item',
                'text'         => '',
                'icon'         => 'fas fa-user',
                'url'          => 'perfil/edit',
                'topnav_right' => true,
            ],
            // Configuración (topnav)
            [
                'type'         => 'navbar-item',
                'text'         => '',
                'icon'         => 'fas fa-cog',
                'url'          => 'configuracion',
                'topnav_right' => true,
            ],
            // Buscador topnav
            [
                'type'         => 'navbar-search',
                'text'         => 'Buscar...',
                'topnav_right' => true,
            ],
        ];
    }
}


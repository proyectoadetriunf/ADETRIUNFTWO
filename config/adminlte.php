<?php

return [

    'title' => 'Panel ADETRIUNF',
    'title_prefix' => '',
    'title_postfix' => '',

    'use_ico_only' => false,
    'use_full_favicon' => false,

    'google_fonts' => [
        'allowed' => true,
    ],

    'logo' => '<b>ADETRIUNF</b>',
    'logo_img' => 'img/IMG_5253.PNG',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_alt' => 'Logo ADETRIUNF',

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'img/IMG_5253.PNG',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'img/IMG_5253.PNG',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_btn' => 'btn-flat btn-primary',

    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    'right_sidebar' => false,

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    'menu' => [

        // 🔍 Buscador en barra lateral
        [
            'type' => 'sidebar-menu-search',
            'text' => 'Buscar...',
        ],

        // 📁 Gestión de Proyectos
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

        // 📅 Calendario
        [
            'text' => 'Calendario',
            'icon' => 'fas fa-calendar-alt',
            'url'  => 'calendario',
        ],

        // 🔔 Notificaciones (topnav)
        [
            'type'            => 'navbar-notification',
            'id'              => 'notificaciones',
            'icon'            => 'fas fa-bell',
            'label'           => 3,
            'label_color'     => 'danger',
            'url'             => '#',
            'topnav_right'    => true,
            'dropdown_mode'   => true,
            'dropdown_flabel' => 'Ver todas las notificaciones',
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

        // 👤 Perfil (topnav)
        [
            'type'         => 'navbar-item',
            'text'         => '',
            'icon'         => 'fas fa-user',
            'url'          => 'perfil/edit',
            'topnav_right' => true,
        ],

        // ⚙️ Configuración (topnav)
        [
            'type'         => 'navbar-item',
            'text'         => '',
            'icon'         => 'fas fa-cog',
            'url'          => 'configuracion',
            'topnav_right' => true,
        ],

        // 🔍 Buscador topnav
        [
            'type'         => 'navbar-search',
            'text'         => 'Buscar...',
            'topnav_right' => true,
        ],
    ],

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    'plugins' => [
        'FullCalendar' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css',
                ],
            ],
        ],
    ],

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    'livewire' => false,

];

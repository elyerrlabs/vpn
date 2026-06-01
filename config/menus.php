<?php

/**
 * This file is part of the Vpn package.
 *
 * It defines shared menu configuration for the identity system.
 * Menus declared here can be merged and consumed by core, system,
 * and third-party modules.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Shared Menu Definitions
    |--------------------------------------------------------------------------
    |
    | Menus defined under the "merge" key are automatically shared across
    | the application. Each menu entry must follow a consistent structure
    | so it can be rendered correctly by the UI layer.
    |
    | Required fields:
    | - id:      Unique menu identifier
    | - name:    Display name
    | - route:   Named route
    | - icon:    Material Design icon (mdi-* webfont)
    | - service: Access scope in the format "group:service"
    |
    */

    'merge' => [

        /*
        |----------------------------------------------------------------------
        | Admin Dashboard Menu
        |----------------------------------------------------------------------
        |
        | Menu items displayed in the administrative dashboard.
        | Intended for system administrators and privileged users.
        |
        */
        'admin_dashboard' => [

            'vpn_servers' => [
                'id' => 'servers',
                'name' => 'VNP Servers',
                'route' => 'module.vpn.admin.servers.index',
                'icon' => 'mdi mdi-store-cog',
                'service' => 'administrator:vpn',
            ],
        ],

        /*
        |----------------------------------------------------------------------
        | User Application Menu
        |----------------------------------------------------------------------
        |
        | Routes displayed in the main application menu for authenticated users.
        |
        */
        'user_routes' => [

            'vpn_wireguard' => [
                'id' => 'peers',
                'name' => 'VPN Wireguard',
                'route' => 'module.vpn.web.users.peers.index',
                'icon' => 'mdi mdi-vpn',
                'service' => 'commerce:vpn',
            ],

        ],

        /*
        |----------------------------------------------------------------------
        | Admin Applications (User Accessible)
        |----------------------------------------------------------------------
        |
        | Administrative applications that a user may access depending on
        | granted permissions or service scopes.
        |
        | These entries are typically shown in admin application sections
        | rather than global dashboards.
        |
        */
        'admin_routes' => [
            'vpn_servers' => [
                'id' => 'servers',
                'name' => 'VNP Servers',
                'route' => 'module.vpn.web.users.servers.index',
                'icon' => 'mdi mdi-store-cog',
                'service' => 'enterprise:vpn-servers',
            ],
        ],

        /*
        |----------------------------------------------------------------------
        | User Settings Menu
        |----------------------------------------------------------------------
        |
        | Menu entries displayed under the user settings section.
        |
        */
        'user_settings' => [

            /*
            'vpn-settings' => [
                'id'      => 'vpn-settings',
                'name'    => 'ElyMod Settings',
                'route'   => 'module.vpn.web.settings',
                'icon'    => 'mdi-cog',
                'service' => 'user:settings',
            ],
            */
        ],


        'admin_settings' => [

            'vpn-settings' => [
                'id' => 'vpn-settings',
                'name' => 'VPN Settings',
                'route' => 'module.vpn.admin.settings.general',
                'icon' => 'mdi mdi-cog',
                'service' => 'administrator:settings',
            ],


        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Module-Specific Admin Routes
    |--------------------------------------------------------------------------
    |
    | Allows injecting additional menu entries into a specific module’s
    | administrative area.
    |
    | The key used here must match the target module key.
    | Each route definition must follow the same structure as shared menus.
    |
    */

    'admin_routes' => [
        /*
        [
            'id' => 'vpn',
            'name' => 'ElyMod',
            'route' => 'module.vpn.web.welcome',
            'icon' => 'mdi-store-cog',
            'service' => true,
            'position' => 8,
        ],
        */
    ],

    'vpn_admin_routes' => [
        [
            'id' => 'servers',
            'name' => 'Servers',
            'route' => 'module.vpn.admin.servers.index',
            'icon' => 'mdi mdi-store-cog',
            'service' => 'administrator:vpn',
            'position' => 1,
        ],

        [
            'id' => 'wireguard',
            'name' => 'Wireguard',
            'route' => 'module.vpn.admin.wireguards.index',
            'icon' => 'mdi mdi-store-cog',
            'service' => 'administrator:vpn',
            'position' => 2,
        ],
    ],

    'vpn_user_routes' => [
        [
            'id' => 'servers',
            'name' => 'Servers',
            'route' => 'module.vpn.web.users.servers.index',
            'icon' => 'mdi mdi-store-cog',
            'service' => 'enterprise:vpn-servers',
            'position' => 1,
        ],

        [
            'id' => 'wireguard',
            'name' => 'Wireguard',
            'route' => 'module.vpn.web.users.wireguards.index',
            'icon' => 'mdi mdi-store-cog',
            'service' => 'enterprise:vpn-servers',
            'position' => 2,
        ],
        [
            'id' => 'peers',
            'name' => 'Wireguard Generator',
            'route' => 'module.vpn.web.users.peers.index',
            'icon' => 'mdi mdi-store-cog',
            'service' => 'commerce:vpn',
            'position' => 3,
        ],
    ]

];

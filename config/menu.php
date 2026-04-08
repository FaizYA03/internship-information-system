<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Navigation Menu
    |--------------------------------------------------------------------------
    |
    | Define the navigation items for the modern layout. Each item can have:
    | - title: The display name
    | - icon: Lucide icon name (e.g., 'home', 'users')
    | - route: The Laravel route name
    | - roles: Array of roles that can access this item. Use '*' for all roles.
    |
    */

    'items' => [
        [
            'title' => 'Dashboard',
            'icon' => 'layout-dashboard',
            'route' => 'home',
            'roles' => ['*'],
        ],
        [
            'title' => 'Profile',
            'icon' => 'user',
            'route' => 'profile', // Ensure this route exists or update it later
            'roles' => ['*'],
        ],
        // Lab specific menus
        [
            'title' => 'Data Laboratorium',
            'icon' => 'database',
            'route' => 'lab.ruangan.index',
            'roles' => ['super_admin', 'admin_lab', 'kepala_lab'],
        ],
        [
            'title' => 'Peminjaman Alat',
            'icon' => 'arrow-right-left',
            'route' => 'siswa.pinjam_alat.index',
            'roles' => ['siswa', 'guru', 'admin_lab'],
        ],
        // Add more menus dynamically as needed
    ]
];

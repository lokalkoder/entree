<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Lokalkoder/Entree Config
    |--------------------------------------------------------------------------
    |
    | This option controls setup for 'lokalkoder/entree' functionality
    |
    */

    'enable_permission' => env('ENTREE_PERMISSION', false),

    'user' => [
        'model' => env('ENTREE_USER_MODEL', 'App\Models\User'),
        'role' => env('ENTREE_USER_ROLE', 'App\Models\Roles'),
    ]
];

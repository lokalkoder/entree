<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Setup Config
    |--------------------------------------------------------------------------
    |
    | This option controls the additional application setup configuration
    |
    */

    'roles' => [
        'admin' => 'super-admin',
        'default' => [
            'owner' => ['access', 'create', 'edit', 'delete']
        ],
        'permit' => ['edit', 'create', 'access', 'delete']
    ]
];

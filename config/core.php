<?php

use Salt\Core\Options\PermissionOptions;
use Salt\Core\Options\RoleOptions;

return [
    'auth0' => array(
        'app' => array(
            'client_id' => env('AUTH0_CLIENT_ID', ''),
            'client_secret' => env('AUTH0_CLIENT_SECRET', ''),
            'db_connection' => env('AUTH0_DB_CONNECTION', '')
        ),
        'api' => array(
            'audience' => env('API_MACHINE_AUDIENCE', ''),
            'client_id' => env('AUTH0_MACHINE_CLIENT_ID', ''),
            'client_secret' => env('AUTH0_MACHINE_CLIENT_SECRET', ''),
            'domain' => env('AUTH0_MACHINE_DOMAIN')
        ),
    ),
    'mail' => array(
        'mandrill' => array(
            'key' => env('MANDRILL_KEY', ''),
            'template' => env('MANDRILL_TEMPLATE', '')
        )
    ),
    'permissions' => PermissionOptions::$permissionsArray,
    'roles' => RoleOptions::$rolesArray,
    'url' => env('APP_URL', '')
];

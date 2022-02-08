<?php

use Salt\Core\Options\PermissionOptions;
use Salt\Core\Options\RoleOptions;

return [
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

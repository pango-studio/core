<?php
return [
    'mail' => array(
        'mandrill' => array(
            'key' => env('MANDRILL_KEY', ''),
            'template' => env('MANDRILL_TEMPLATE', '')
        )
    ),
    'permissions' => \Salt\Core\Options\PermissionOptions::$permissionsArray,
    'roles' => \Salt\Core\Options\RoleOptions::$rolesArray,
    'url' => env('APP_URL', ''),
    'user' => \Salt\Core\Models\User::class
];

<?php

namespace Salt\Core\Enums;

/**
 * If using PHP 8.1+, it may make sense
 * to use enums as an approach to store
 * permissions.
 */

enum UserPermissions: string
{
    case VIEW_ADMIN_DASHBOARD = 'view-admin-dashboard';
    case MANAGE_USERS = 'manage-users';

    public function label(): string
    {
        return match ($this) {
            UserPermissions::VIEW_ADMIN_DASHBOARD => 'View admin dashboard',
            UserPermissions::MANAGE_USERS => 'Manage users'
        };
    }

    public function description(): string
    {
        return match ($this) {
            UserPermissions::VIEW_ADMIN_DASHBOARD => 'Access the administration dashboard',
            UserPermissions::MANAGE_USERS => 'Manage application users'
        };
    }

    public function dependencies(): array
    {
        return match ($this) {
            UserPermissions::VIEW_ADMIN_DASHBOARD => [],
            UserPermissions::MANAGE_USERS => [
                UserPermissions::VIEW_ADMIN_DASHBOARD,
            ],
        };
    }
}

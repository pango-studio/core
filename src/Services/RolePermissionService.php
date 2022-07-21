<?php

namespace Salt\Core\Services;

use Salt\Core\Models\Role;
use Salt\Core\Models\User;

class RolePermissionService
{
    public static function removeUserRolePermissions(int $role_id, int $user_id)
    {
        $role = Role::find($role_id);
        $user = User::find($user_id);
        $role_permissions = $role->permissions()->get();

        $user->permissions()->detach($role_permissions);

        self::syncUserRolePermissions($user_id);

        return true;
    }

    public static function syncUserRolePermissions(int $user_id)
    {
        $user = User::find($user_id);
        $role_permissions = $user->rolePermissions()->pluck('id');

        $user->permissions()->syncWithoutDetaching($role_permissions);


        return true;
    }

    public static function syncRolePermissions(int $role_id)
    {
        $users = User::whereHas('roles', function ($q) use ($role_id) {
            $q->where('role_id', $role_id);
        })->get();
        foreach ($users as $key => $user) {
            self::removeUserRolePermissions($role_id, $user->id);
        }

        return true;
    }
}

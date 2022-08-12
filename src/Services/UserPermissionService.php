<?php

namespace Salt\Core\Services;

use Salt\Core\Models\Role;
use Salt\Core\Models\User;
use Salt\Core\Models\Permission;
use Salt\Core\Models\UserPermission;
use Salt\Core\Exceptions\PermissionDependencyException;

class UserPermissionService
{
    public static function verifyDependencies( UserPermission $user_permission ){
        $permission = Permission::find($user_permission->permission_id);
        $user = User::find($user_permission->user_id);
        $dependencies = $permission->dependencies;
        foreach( $dependencies as $perm ){
            if( !$user->hasPermission($perm) ){
                throw new PermissionDependencyException('User requires ' . $perm->name . ' permission in order to be granted ' . $permission->name );
                return false;
            }
        }
    }
}

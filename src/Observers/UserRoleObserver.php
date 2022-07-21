<?php

namespace Salt\Core\Observers;

use Salt\Core\Models\UserRole;
use Salt\Core\Services\RolePermissionService;

class UserRoleObserver
{
    /**
     * Handle the UserRole "created" event.
     *
     * @param  \App\UserRole  $user_role
     * @return void
     */
    public function created(UserRole $user_role)
    {
        $rp_serivce = new RolePermissionService();

        $rp_serivce->syncUserRolePermissions($user_role->user_id);
    }

    /**
     * Handle the UserRole "deleted" event.
     *
     * @param  \App\UserRole  $user_role
     * @return void
     */
    public function deleted(UserRole $user_role)
    {
        $rp_serivce = new RolePermissionService();
        $rp_serivce->removeUserRolePermissions($user_role->role_id, $user_role->user_id);
    }
}

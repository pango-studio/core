<?php

namespace Salt\Core\Observers;

use Salt\Core\Models\RolePermission;
use Salt\Core\Services\RolePermissionService;

class RolePermissionObserver
{
    /**
     * Handle the RolePermission "created" event.
     *
     * @param  RolePermission  $role_permission
     * @return void
     */
    public function created(RolePermission $role_permission)
    {
        $rp_serivce = new RolePermissionService();
        $rp_serivce->syncRolePermissions($role_permission->role_id);
    }

    /**
     * Handle the RolePermission "deleted" event.
     *
     * @param  RolePermission  $role_permission
     * @return void
     */
    public function deleted(RolePermission $role_permission)
    {
        $rp_serivce = new RolePermissionService();
        $rp_serivce->syncRolePermissions($role_permission->role_id);
       
    }

}

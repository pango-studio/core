<?php

namespace Salt\Core\Observers;

use Salt\Core\Models\User;
use Salt\Core\Models\UserPermission;
use Salt\Core\Services\UserPermissionService;

class PermissionDependenciesObserver
{
    public function __construct(UserPermissionService $user_permission_service)
    {
        $this->user_permission_service = $user_permission_service;
    }

    /**
     * Handle the UserPermission "creating" event.
     * Throws an error if the user requires more
     * permissions before adding this one.
     *
     * @param  UserPermission  $user_permission
     * @return void
     */
    public function creating(UserPermission $user_permission)
    {
        $this->user_permission_service->verifyDependencies($user_permission);
    }
}

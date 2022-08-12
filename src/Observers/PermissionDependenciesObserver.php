<?php

namespace Salt\Core\Observers;

use Salt\Core\Models\User;
use Salt\Core\Models\UserPermission;
use Salt\Core\Services\UserPermissionService;

class PermissionDependenciesObserver
{
    public function __construct(UserPermissionService $userPermissionService)
    {
        $this->userPermissionService = $userPermissionService;
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
        $this->userPermissionService->verifyDependencies($user_permission);
    }
}

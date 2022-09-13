<?php

namespace Salt\Core\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Salt\Core\Observers\PermissionDependenciesObserver;

class UserPermission extends Pivot
{
    protected $table = 'permission_user';

    public static function boot()
    {
        parent::boot();
        UserPermission::observe(PermissionDependenciesObserver::class);
    }
}

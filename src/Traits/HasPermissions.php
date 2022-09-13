<?php

namespace Salt\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Salt\Core\Models\Permission;
use Salt\Core\Models\UserPermission;

trait HasPermissions
{
    public function scopeOfPermission(Builder $query, Permission $permission = null)
    {
        if (!$permission) {
            return $query;
        }

        return $query->whereHas('permissions', function ($query) use ($permission) {
            return $query->where('permission_id', $permission->id);
        });
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user')->withTimestamps()->using(UserPermission::class);
    }

    public function hasPermission($permission)
    {
        return $this->permissions()->where('name', $permission)->exists();
    }
}

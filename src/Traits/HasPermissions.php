<?php

namespace Salt\Core\Traits;


use Salt\Core\Models\Permission;
use Illuminate\Database\Eloquent\Builder;

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
        return $this->belongsToMany(Permission::class, 'permission_user')->withTimestamps();
    }

    public function hasPermission($permission)
    {
        return $this->permissions()->where('name', $permission)->exists();
    }


   
 
}

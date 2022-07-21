<?php

namespace Salt\Core\Traits;

use Salt\Core\Models\Role;
use Salt\Core\Models\UserRole;
use Illuminate\Database\Eloquent\Builder;

trait HasRoles
{
    public function scopeOfRole(Builder $query, Role $role = null)
    {
        if (!$role) {
            return $query;
        }

        return $query->whereHas('roles', function ($query) use ($role) {
            return $query->where('role_id', $role->id);
        });
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function addRole($role)
    {
        return $this->roles()
            ->syncWithoutDetaching(Role::where('name', $role)->first());
    }

 

    public function roles()
    {
        return $this->belongsToMany(Role::class)->using(UserRole::class);
    }

    public function rolePermissions()
    {
        return $this
            ->roles()
            ->whereHas('permissions')
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id')
            ->values();
    }
 
}

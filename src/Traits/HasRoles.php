<?php

namespace Salt\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Salt\Core\Models\Role;

trait HasRoles
{
    public function scopeOfRole(Builder $query, Role $role = null)
    {
        if (!$role) return $query;

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
            ->syncWithoutDetaching(Role::where('name', $role)->firstOrFail());
    }

    public function hasPermission($permission)
    {
        return $this->permissions()->contains('name', $permission);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this
            ->roles()
            ->whereHas('permissions')
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('name')
            ->values();
    }
}

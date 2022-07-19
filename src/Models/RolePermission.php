<?php

namespace Salt\Core\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Salt\Core\Models\Role
 *
 * @property string $name
 * @property string $label
 */
class RolePermission extends Model
{
    protected $table = 'permission_role';
    protected $guarded = [];

    public static function boot() {
        parent::boot();
        static::creating(function ($role_permission) {
            $rp_serivce = new RolePermissionService();
            $rp_serivce->syncRolePermissions($role_permission->role_id);
        });

        static::deleting(function($role_permission) { 
            $rp_serivce = new RolePermissionService();
            $rp_serivce->syncRolePermissions($role_permission->role_id);
        });
    }

}

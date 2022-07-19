<?php

namespace Salt\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Salt\Core\Services\RolePermissionService;

/**
 * Salt\Core\Models\Role
 *
 * @property string $name
 * @property string $label
 */
class UserRole extends Model
{
    protected $table = 'role_user';
    protected $guarded = [];

    public static function boot() {
        parent::boot();
        static::creating(function ($user_role) {
            $rp_serivce = new RolePermissionService();
            $rp_serivce->syncUserRolePermissions($user_role->user_id);
        });

        static::deleting(function($user_role) { 
            $rp_serivce = new RolePermissionService();
            $rp_serivce->removeUserRolePermissions($user_role->role_id, $user_role->user_id);
        });
    }

}

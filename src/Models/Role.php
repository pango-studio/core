<?php

namespace Salt\Core\Models;

use Salt\Core\Models\UserRole;
use Salt\Core\Models\RolePermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Salt\Core\Models\Role
 *
 * @property string $name
 * @property string $label
 */
class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function options()
    {
        return config('core.roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withTimestamps()->using(RolePermission::class);
    }

    public function users()
    {
        return $this->belongsToMany(config('auth.providers.users.model'))->using(UserRole::class);

    }
}

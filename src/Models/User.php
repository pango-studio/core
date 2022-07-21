<?php

namespace Salt\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Salt\Core\Traits\HasImpersonation;
use Salt\Core\Traits\HasRoles;
use Salt\Core\Traits\PerPagePaginateTrait;
use Salt\Core\Traits\SearchOrSortTrait;

/**
 * Salt\Core\Models\User
 *
 * @property string $name
 * @property string $email
 * @property string $sub
 */
class User extends Authenticatable
{
    use HasFactory;
    use HasImpersonation;
    use HasRoles;
    use PerPagePaginateTrait;
    use SearchOrSortTrait;

    protected $fillable = [
        'name', 'email', 'sub',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user')->withTimestamps();
    }

    public function hasPermission($permission)
    {
        return $this->permissions()->where('name', $permission)->exists();
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

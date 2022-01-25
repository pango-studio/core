<?php

namespace Salt\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Salt\Core\Models\Permission
 * 
 * @property string $name
 */
class Permission extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function options()
    {
        return config('core.permissions');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_roles')->withTimestamps();
    }
}

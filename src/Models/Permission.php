<?php

namespace Salt\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public static function permissionOptions()
    {
        return config('core.permissions');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_roles')->withTimestamps();
    }
}

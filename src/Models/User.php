<?php

namespace Salt\Core\Models;

use Salt\Core\Traits\HasRoles;
use Salt\Core\Models\Permission;
use Salt\Core\Traits\HasImpersonation;
use Salt\Core\Traits\SearchOrSortTrait;
use Salt\Core\Traits\PerPagePaginateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Salt\Core\Traits\HasPermissions;

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
    use HasPermissions;
    use PerPagePaginateTrait;
    use SearchOrSortTrait;

    protected $fillable = [
        'name', 'email', 'sub',
    ];

 
}

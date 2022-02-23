<?php

namespace Salt\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Salt\Core\Traits\HasImpersonation;
use Salt\Core\Traits\HasRoles;

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

    protected $fillable = [
        'name', 'email', 'sub',
    ];
}

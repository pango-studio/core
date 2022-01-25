<?php

namespace Salt\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Salt\Core\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use HasRoles;
    /** @var string The user's email address */
    public $email;

    /** @var string The user's name */
    public $name;

    /** @var string The user's Auth0 ID */
    public $sub;

    protected $fillable = [
        'name', 'email', 'sub',
    ];
}

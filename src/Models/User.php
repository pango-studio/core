<?php

namespace Salt\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Salt\Core\Traits\HasRoles;

class User extends Authenticatable
{
    /** @var string The user's email address */
    public $email;

    /** @var string The user's name */
    public $name;

    /** @var string The user's Auth0 ID */
    public $sub;

    use HasFactory;
    use HasRoles;

    protected $fillable = [
        'name', 'email', 'sub',
    ];
}

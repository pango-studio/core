<?php

namespace Salt\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Salt\Core\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use HasRoles;

    protected $fillable = [
        'name', 'email', 'sub',
    ];
}

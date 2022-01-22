<?php

namespace Salt\Core\Tests;

use Illuminate\Database\Eloquent\Model;
use Salt\Core\Traits\HasRoles;

class User extends Model
{
    use HasRoles;

    protected $fillable = ['email'];

    public $timestamps = false;

    protected $table = 'users';
}

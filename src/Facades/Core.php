<?php

namespace Salt\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Salt\Core\Core
 */
class Core extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'core';
    }
}

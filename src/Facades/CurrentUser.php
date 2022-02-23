<?php


namespace Salt\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * This facade is used to return the current user object.
 * 
 * To get the currently authenticated user, use CurrentUser::get();
 * 
 * To disable impersonation of the data, use CurrentUser::disableImpersonation()->get()
 */
class CurrentUser extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'currentuser';
    }
}

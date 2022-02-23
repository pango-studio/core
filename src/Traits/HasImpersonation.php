<?php

namespace Salt\Core\Traits;

use Illuminate\Support\Facades\Session;

trait HasImpersonation
{
    /**
     * Adds a new session variable 'impersonation' which contains the ID of the
     * user being impersonated
     *
     * @param Integer $id
     * @return void
     */
    public static function startImpersonating($id): void
    {
        Session::put('impersonation', $id);
    }

    /**
     * Removes the 'impersonation' variable from the session
     *
     * @return void
     */
    public static function stopImpersonating(): void
    {
        Session::forget('impersonation');
    }

    /**
     * Checks if the 'impersonation' variable exists in the session
     *
     * @return bool
     */
    public static function isImpersonating(): bool
    {
        return Session::has('impersonation');
    }

    /**
     * Get the user model for the currently impersonated user
     *
     * @return Object
     */
    public static function getImpersonatedUser()
    {
        $user = new (config('core.user'));

        return $user->find(Session::get('impersonation'));
    }
}

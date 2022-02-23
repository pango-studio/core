<?php

namespace Salt\Core\Helpers;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class GetCurrentUser
{
    protected $user;
    protected $userModel;
    protected bool $enableImpersonation;

    public function __construct(Authenticatable $userModel)
    {
        $this->userModel = $userModel;
        $this->user = Auth::user() ? $this->userModel::find(Auth::user()->id) : null;
        $this->enableImpersonation = true;
    }

    /**
     * Returns the model for the currently authenticated user. If there is an active impersonation setting,
     * returns the model for the impersonated user instead
     */
    public function get(): ?Authenticatable
    {
        if ($this->enableImpersonation && $this->userModel::isImpersonating()) {
            return $this->userModel::getImpersonatedUser();
        }

        return $this->user;
    }

    /**
     * Turns off impersonation. The authenticated user will be returned regardless of the active impersonation session
     */
    public function disableImpersonation(): static
    {
        $this->enableImpersonation = false;

        return $this;
    }
}

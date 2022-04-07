<?php

namespace Salt\Core\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Salt\Core\Facades\CurrentUser;

class PermissionChecker
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $permission
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next, string $permission)
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = CurrentUser::get();
        if (!$user->hasPermission($permission)) {
            throw new AuthorizationException("You do not have permission to view this page");
        }

        return $next($request);
    }
}

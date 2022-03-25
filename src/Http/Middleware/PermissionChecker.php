<?php

namespace Salt\Core\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

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

        $user = $request->user();
        if (!$user->hasPermission($permission)) {
            throw new AuthorizationException(__('middleware.noPermission'));
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (! $request->user()) {
            return redirect()->route('admin.login');
        }

        if (! $request->user()->can($permission)) {
            abort(403, 'You do not have the required permissions to access this page.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (! $request->user()) {
            return redirect()->route('admin.login');
        }

        if (! $request->user()->hasRole($role)) {
            Auth::logout();
            abort(403, 'You do not have the required permissions to access this page.');
        }

        return $next($request);
    }
}

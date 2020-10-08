<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Acl
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $roles = explode('|', $role);
        if ($request->user() === null || !$request->user()->hasAnyRole($roles)) {
            abort(403);
        }
        return $next($request);
    }
}

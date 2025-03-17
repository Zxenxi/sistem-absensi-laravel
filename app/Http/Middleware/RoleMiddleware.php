<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        // Jika ingin mendukung banyak role, gunakan in_array
        if (!$request->user() || !in_array($request->user()->role, explode('|', $role))) {
            abort(403, 'Unauthorized.');
        }
        return $next($request);
    }
}
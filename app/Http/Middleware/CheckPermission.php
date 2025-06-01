<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permisison): Response
    {
        $permissions = session('permissions') ? session('permissions')->toArray() : [];
        if (!in_array($permisison, $permissions)) {
            abort(403);
        }
        return $next($request);
    }
}

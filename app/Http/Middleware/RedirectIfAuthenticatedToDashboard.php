<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedToDashboard
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {   
        // dd(Auth::check());
        // Nếu đã đăng nhập thì chuyển hướng sang route admin.dashboard
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return $next($request);
    }
}

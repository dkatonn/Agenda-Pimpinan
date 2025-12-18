<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LogoutIfPublic
{
    public function handle($request, Closure $next)
    {
        
        if (Auth::check() && !$request->is('admin/*')) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
        }

        return $next($request);
    }
}

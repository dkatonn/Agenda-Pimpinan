<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = $user->role;

        if (strtolower($userRole) === strtolower($role)) {
            return $next($request);
        }

        return redirect('/admin/dashboard')
            ->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
    }
}
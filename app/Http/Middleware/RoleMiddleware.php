<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles) // Bisa multiple role
    {
        if (!Auth::check() || !in_array(Auth::user()->role->nama_role, $roles)) {
            return redirect('/login')->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}

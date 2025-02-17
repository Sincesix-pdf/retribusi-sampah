<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check() || Auth::user()->role->nama_role !== $role) {
            return abort(403, 'Unauthorized'); // Akses ditolak jika bukan role yang sesuai
        }

        return $next($request);
    }
}

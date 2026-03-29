<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleRequest
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        // Cek apakah role user ada di daftar yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika tidak diizinkan → redirect berdasarkan role
        if (in_array($user->role, ['user'])) {
            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        if (in_array($user->role, ['admin'])) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Fallback: jika role tidak dikenali sama sekali
        abort(403, 'Akses ditolak. Role Anda tidak diizinkan.');
    }
}
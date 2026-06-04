<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        // Cek jika role yang diminta adalah 'admin'
        if ($role === 'admin' && !$user->isAdmin()) {
            abort(403, 'Akses Ditolak: Halaman ini khusus Admin.');
        }

        // Cek jika role yang diminta adalah 'pustakawan' (Admin juga boleh masuk)
        if ($role === 'pustakawan' && !$user->isPustakawan() && !$user->isAdmin()) {
            abort(403, 'Akses Ditolak: Halaman ini khusus Pustakawan.');
        }

        return $next($request);
    }
}

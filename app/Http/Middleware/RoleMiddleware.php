<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {

            return redirect()
                ->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil role user login
        $userRole = Auth::user()->role;

        // Cek apakah role user sesuai
        if (!in_array($userRole, $roles)) {

            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
<?php
// FILE: app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('home');
        }

        $user = auth()->user();

        // Cek apakah role user ada di daftar role yang diizinkan
        if (!in_array($user->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Cek apakah akun aktif (bukan suspended)
        if (!$user->is_active || $user->is_suspended) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('home')
                ->with('error', 'Akun Anda telah dinonaktifkan atau disuspend.');
        }

        return $next($request);
    }
}
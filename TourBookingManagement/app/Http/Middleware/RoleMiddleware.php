<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * RoleMiddleware
 *
 * Middleware untuk membatasi akses berdasarkan role user.
 * Contoh penggunaan di route:
 *
 *   Route::middleware(['role:admin'])->group(function () {
 *       // hanya admin yang boleh akses
 *   });
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request   Request yang masuk
     * @param  \Closure                  $next      Callback untuk request berikutnya
     * @param  string                    $role      Role yang wajib dimiliki user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Jika user belum login atau rolenya tidak sesuai → tolak akses
        if (! Auth::check() || Auth::user()->role !== $role) {
            abort(403, 'Unauthorized');
        }

        // Jika role sesuai → lanjutkan request
        return $next($request);
    }
}

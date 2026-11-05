<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware para proteger el panel del SuperAdmin
 * Verifica que exista una sesión activa de administrador general.
 */
class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('superadmin_logged')) {
            return redirect()->route('superadmin.login')
                ->with('error', 'Debes iniciar sesión como administrador.');
        }
        return $next($request);
    }
}

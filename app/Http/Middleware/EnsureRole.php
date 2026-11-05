<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para control de acceso por roles (RBAC)
 * Verifica que el usuario autenticado tenga el rol requerido (superadmin, admin, parent).
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            abort(403, 'Acceso no autorizado.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\Central\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * ResolveTenant — Detecta el subdominio y cambia la conexión de base de datos.
 * Modificado para soportar entorno local sin subdominios usando SQLite.
 */
class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost(); // Ej: atlas.academiahe5.com
        $mainDomain = config('app.main_domain', 'academiahe5.com');

        $subdomain = str_replace('.' . $mainDomain, '', $host);

        if ($host === '127.0.0.1' || $host === 'localhost' || $host === 'saas.academiahe5.com' || $subdomain === 'saas') {
            if (auth()->check()) {
                // En entorno local o dominio central, cargamos la academia correspondiente al usuario en sesión
                $tenant = Tenant::find(auth()->user()->tenant_id);
                if (!$tenant) {
                    abort(404, 'No tenants found for your user.');
                }
                $subdomain = $tenant->subdomain;
            } else {
                return $next($request);
            }
        }

        // Si es el dominio principal o www, no es un tenant
        if ($subdomain === $host || $subdomain === 'www' || $subdomain === $mainDomain) {
            return $next($request);
        }

        // Buscar tenant en la DB central
        $tenant = Tenant::where('subdomain', $subdomain)
            ->where('status', 'active')
            ->first();

        if (!$tenant) {
            abort(404, 'Academia no encontrada o inactiva.');
        }

        // Cambiar dinámicamente la conexión de base de datos al tenant (usando SQLite)
        Config::set('database.connections.tenant', [
            'driver'   => 'sqlite',
            'database' => database_path($tenant->db_name . '.sqlite'),
            'prefix'   => '',
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        // Guardar tenant en el request para acceso global
        $request->merge(['_tenant' => $tenant]);
        app()->instance('tenant', $tenant);

        return $next($request);
    }
}

<?php

namespace App\Services;

use App\Models\Central\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * TenantProvisioningService
 * Crea automáticamente la base de datos y ejecuta las migraciones
 * de un nuevo tenant después de que su pago es confirmado por Stripe.
 * Modificado para usar SQLite para demo local.
 */
class TenantProvisioningService
{
    public function provision(Tenant $tenant): bool
    {
        if ($tenant->db_name) {
            Log::info("[Provisioning] Tenant {$tenant->subdomain} ya tiene DB asignada.");
            return true;
        }

        try {
            $dbName = 'u718028468_' . Str::slug($tenant->subdomain, '');

            // Crear base de datos en SQLite
            $dbPath = database_path($dbName . '.sqlite');
            touch($dbPath);

            // Guardar credenciales en el tenant
            $tenant->update([
                'db_name'     => $dbName,
                'db_user'     => 'sqlite',
                'db_password' => '',
                'db_host'     => '127.0.0.1',
            ]);

            // Conectar a la nueva DB y ejecutar migraciones de tenant
            Config::set('database.connections.tenant_provision', [
                'driver'   => 'sqlite',
                'database' => $dbPath,
                'prefix'   => '',
            ]);

            DB::purge('tenant_provision');

            // Ejecutar migraciones en la carpeta /tenant
            Artisan::call('migrate', [
                '--path'     => 'database/migrations/tenant',
                '--database' => 'tenant_provision',
                '--force'    => true,
            ]);

            // Crear el subdominio en Hostinger automáticamente apuntando a saas/public
            $this->createHostingerSubdomain($tenant->subdomain);

            Log::info("[Provisioning] Academia '{$tenant->subdomain}' creada exitosamente: DB {$dbName}");
            return true;

        } catch (\Exception $e) {
            Log::error("[Provisioning] Error creando tenant {$tenant->subdomain}: " . $e->getMessage());
            return false;
        }
    }

    private function createHostingerSubdomain(string $subdomain): void
    {
        $token = env('HOSTINGER_API_TOKEN');
        if (!$token) {
            Log::warning("[Provisioning] No se encontró HOSTINGER_API_TOKEN para crear el subdominio {$subdomain}");
            return;
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->timeout(15)
                ->post('https://developers.hostinger.com/api/hosting/v1/accounts/u718028468/websites/academiahe5.com/subdomains', [
                    'subdomain' => strtolower($subdomain),
                    'directory' => 'saas/public'
                ]);

            if ($response->successful()) {
                Log::info("[Provisioning Hostinger] Subdominio '{$subdomain}' creado exitosamente en Hostinger apuntando a saas/public.");
            } else {
                Log::error("[Provisioning Hostinger] Error creando subdominio '{$subdomain}': " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("[Provisioning Hostinger] Excepción al crear subdominio '{$subdomain}': " . $e->getMessage());
        }
    }
}

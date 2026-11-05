<?php

namespace App\Services;

use App\Models\Central\Tenant;
use Illuminate\Support\Facades\DB;

class PlanManager
{
    /**
     * Valida si el tenant actual ha alcanzado el límite de jugadores de su plan.
     */
    public function canAddPlayer(Tenant $tenant): bool
    {
        $limits = $tenant->planLimits();
        if ($limits['players'] >= 9999) return true; // Ilimitado

        // Contar jugadores actuales en la base de datos sqlite del tenant
        $currentPlayers = DB::connection('tenant')->table('players')->count();
        return $currentPlayers < $limits['players'];
    }

    /**
     * Valida si el tenant actual ha alcanzado el límite de categorías.
     */
    public function canAddCategory(Tenant $tenant): bool
    {
        $limits = $tenant->planLimits();
        if ($limits['categories'] >= 999) return true;

        $currentCategories = DB::connection('tenant')->table('categories')->count();
        return $currentCategories < $limits['categories'];
    }
}

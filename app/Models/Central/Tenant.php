<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo central: representa a cada cliente (academia) del SaaS.
 * Vive en la base de datos central (la de academiahe5.com principal).
 */
class Tenant extends Model
{
    protected $fillable = [
        'subdomain',        // atlas → atlas.academiahe5.com
        'academy_name',     // "Academia Atlas FC"
        'owner_name',
        'owner_email',
        'plan',             // basico | elite | premium
        'status',           // active | suspended | trial | cancelled
        'db_name',          // u718028468_atlas
        'db_user',          // u718028468_atlasuser
        'db_password',      // encriptado
        'db_host',
        'stripe_customer_id',
        'stripe_subscription_id',
        'stripe_public_key',
        'stripe_secret_key',
        'logo_path',
        'color_palette',
        'trial_ends_at',
        'subscription_ends_at',
    ];

    protected $casts = [
        'trial_ends_at'        => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    protected $hidden = ['db_password'];

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isTrial(): bool
    {
        return $this->status === 'trial' && $this->trial_ends_at?->isFuture();
    }

    /**
     * Límites del plan para validación en middleware de tenant.
     */
    public function planLimits(): array
    {
        return match($this->plan) {
            'basico'  => ['categories' => 3,  'players' => 40,  'seasons' => 1, 'admins' => 1],
            'elite'   => ['categories' => 6,  'players' => 150, 'seasons' => 2, 'admins' => 2],
            'premium' => ['categories' => 999,'players' => 9999,'seasons' => 99,'admins' => 99],
            default   => ['categories' => 1,  'players' => 10,  'seasons' => 1, 'admins' => 1],
        };
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Central\Tenant;
use App\Services\TenantProvisioningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

/**
 * Controlador de Webhooks de Stripe
 * Procesa notificaciones automáticas cuando un pago o suscripción se completa con éxito.
 * Valida el evento directamente con la API de Stripe antes de aprovisionar la cuenta.
 */
class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Respuesta inmediata 200 para evitar que la pasarela reintente por timeout de red
        $eventId  = $request->input('id');
        $eventType = $request->input('type');

        if (!$eventId || !$eventType) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        Log::info("[SaaS Webhook] Evento recibido: {$eventType} | ID: {$eventId}");

        try {
            $stripe = new StripeClient(config('services.stripe.secret'));
            $event  = $stripe->events->retrieve($eventId);

            match ($event->type) {
                'checkout.session.completed'       => $this->handleCheckoutCompleted($event->data->object),
                'invoice.paid'                     => $this->handleInvoicePaid($event->data->object),
                'invoice.payment_failed'           => $this->handlePaymentFailed($event->data->object),
                'customer.subscription.deleted'    => $this->handleSubscriptionDeleted($event->data->object),
                default => Log::info("[SaaS Webhook] Evento ignorado: {$event->type}"),
            };

        } catch (\Exception $e) {
            Log::error("[SaaS Webhook] Error procesando {$eventId}: " . $e->getMessage());
        }

        return response()->json(['status' => 'ok'], 200);
    }

    private function handleCheckoutCompleted(object $session): void
    {
        $email    = $session->customer_details?->email;
        $planSlug = $session->metadata?->plan ?? 'basico';
        $subdomain = $session->metadata?->subdomain ?? null;

        if (!$email || !$subdomain) {
            Log::warning('[SaaS Webhook] checkout.session.completed sin email o subdomain en metadata');
            return;
        }

        // Activar o crear el tenant
        $tenant = Tenant::where('owner_email', $email)->first();
        if ($tenant) {
            $tenant->update([
                'status'                 => 'active',
                'plan'                   => $planSlug,
                'stripe_customer_id'     => $session->customer,
                'stripe_subscription_id' => $session->subscription,
                'subscription_ends_at'   => now()->addMonth(),
            ]);

            // Provisionar la base de datos del tenant si aún no existe
            app(TenantProvisioningService::class)->provision($tenant);

            Log::info("[SaaS Webhook] Tenant activado: {$email} → Plan {$planSlug}");
        }
    }

    private function handleInvoicePaid(object $invoice): void
    {
        $tenant = Tenant::where('stripe_customer_id', $invoice->customer)->first();
        if ($tenant) {
            $tenant->update([
                'status'               => 'active',
                'subscription_ends_at' => now()->addMonth(),
            ]);
            Log::info("[SaaS Webhook] Suscripción renovada: {$tenant->owner_email}");
        }
    }

    private function handlePaymentFailed(object $invoice): void
    {
        $tenant = Tenant::where('stripe_customer_id', $invoice->customer)->first();
        if ($tenant) {
            $tenant->update(['status' => 'suspended']);
            Log::warning("[SaaS Webhook] Pago fallido → Tenant suspendido: {$tenant->owner_email}");
        }
    }

    private function handleSubscriptionDeleted(object $subscription): void
    {
        $tenant = Tenant::where('stripe_subscription_id', $subscription->id)->first();
        if ($tenant) {
            $tenant->update(['status' => 'cancelled']);
            Log::info("[SaaS Webhook] Suscripción cancelada: {$tenant->owner_email}");
        }
    }
}

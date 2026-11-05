<?php

namespace App\Http\Controllers;

use App\Models\Central\Tenant;
use App\Models\User;
use App\Services\TenantProvisioningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Stripe\StripeClient;

class CheckoutController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'plan'          => 'required|in:basico,elite,premium',
            'academy_name'  => 'required|string|max:100',
            'subdomain'     => 'required|string|alpha_dash|max:30|unique:tenants,subdomain',
            'owner_name'    => 'required|string|max:100',
            'owner_email'   => 'required|email|unique:tenants,owner_email',
            'password'      => 'required|string|min:8|confirmed',
        ]);

        $prices = [
            'basico'  => config('services.stripe.price_basico'),
            'elite'   => config('services.stripe.price_elite'),
            'premium' => config('services.stripe.price_premium'),
        ];

        // Crear tenant en estado 'trial' antes del pago
        $tenant = Tenant::create([
            'subdomain'    => $request->subdomain,
            'academy_name' => $request->academy_name,
            'owner_name'   => $request->owner_name,
            'owner_email'  => $request->owner_email,
            'plan'         => $request->plan,
            'status'       => 'trial',
            'trial_ends_at'=> now()->addDays(14),
        ]);

        // Crear al dueño como administrador inmediatamente
        User::create([
            'name' => $request->owner_name,
            'email' => $request->owner_email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'tenant_id' => $tenant->id,
        ]);

        // Crear sesión de Stripe Checkout (modo TEST)
        $stripe  = new StripeClient(config('services.stripe.secret'));
        $session = $stripe->checkout->sessions->create([
            'mode'                => 'subscription',
            'payment_method_types'=> ['card'],
            'line_items'         => [['price' => $prices[$request->plan], 'quantity' => 1]],
            'customer_email'     => $request->owner_email,
            'metadata'           => [
                'plan'      => $request->plan,
                'subdomain' => $request->subdomain,
                'tenant_id' => $tenant->id,
            ],
            'success_url'        => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'         => route('checkout.cancelled'),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        if (!$request->session_id) {
            return redirect('/');
        }

        $stripe  = new StripeClient(config('services.stripe.secret'));
        try {
            $session = $stripe->checkout->sessions->retrieve($request->session_id);
            $tenantId = $session->metadata->tenant_id;
            
            $tenant = Tenant::find($tenantId);
            if ($tenant) {
                // Provisionar tenant si no tiene DB
                if (!$tenant->db_name) {
                    $tenant->update(['status' => 'active']);
                    app(TenantProvisioningService::class)->provision($tenant);
                }

                // Auto-login al dueño
                $user = User::where('email', $tenant->owner_email)->first();
                if ($user) {
                    Auth::login($user);
                }
            }
        } catch (\Exception $e) {
            // Si la consulta a Stripe falla en entorno de pruebas, permitimos continuar a la vista
        }

        return view('checkout-exitoso', ['session_id' => $request->session_id]);
    }
}

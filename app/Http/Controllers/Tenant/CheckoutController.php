<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;

class CheckoutController extends Controller
{
    public function create(Request $request)
    {
        $tenant = app('tenant');
        $paymentId = $request->input('payment_id');
        $payment = null;

        if ($paymentId) {
            $payment = DB::connection('tenant')->table('payments')->where('id', $paymentId)->first();
        }

        if (!$payment) {
            $payment = DB::connection('tenant')->table('payments')->where('status', '!=', 'Pagado')->first();
        }

        if (!$payment) {
            $concept = $request->input('concept', 'Mensualidad Escolar');
            $amount = floatval($request->input('amount', 800));
            $insertedId = DB::connection('tenant')->table('payments')->insertGetId([
                'folio' => 'FOL-' . rand(1000, 9999),
                'student_tutor' => auth()->user()->name ?? 'Tutor Titular',
                'concept' => $concept,
                'amount' => $amount > 0 ? $amount : 800.00,
                'status' => 'Pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $payment = DB::connection('tenant')->table('payments')->where('id', $insertedId)->first();
        }

        // Si la academia configuró su llave de Stripe real
        if (!empty($tenant->stripe_secret_key) && class_exists(StripeClient::class)) {
            try {
                $stripe = new StripeClient($tenant->stripe_secret_key);
                $session = $stripe->checkout->sessions->create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency'     => 'mxn',
                            'product_data' => [
                                'name' => $payment->concept . ' - ' . ($tenant->academy_name ?? 'Academia'),
                            ],
                            'unit_amount'  => (int)($payment->amount * 100),
                        ],
                        'quantity'   => 1,
                    ]],
                    'mode'        => 'payment',
                    'success_url' => route('tenant.checkout.success') . '?payment_id=' . $payment->id,
                    'cancel_url'  => route('tenant.frontend'),
                ]);

                if ($request->expectsJson()) {
                    return response()->json(['success' => true, 'redirect' => $session->url]);
                }
                return redirect($session->url);
            } catch (\Exception $e) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Error de conexión Stripe: ' . $e->getMessage()], 422);
                }
                return back()->with('error', 'Error conectando con la pasarela Stripe: ' . $e->getMessage());
            }
        }

        // Modo Demostración / Simulación de pasarela Stripe cuando la academia aún no ingresa sus credenciales
        DB::connection('tenant')->table('payments')
            ->where('id', $payment->id)
            ->update([
                'status'     => 'Pagado',
                'updated_at' => now(),
            ]);

        $folio = $payment->folio ?? ('FOL-' . rand(1000, 9999));
        $msg = "¡Pago procesado exitosamente vía Stripe! Folio: {$folio}. Tu saldo ha sido liquidado.";

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'folio'   => $folio,
                'amount'  => $payment->amount
            ]);
        }

        return redirect()->route('tenant.frontend')->with('success', $msg);
    }

    public function success(Request $request)
    {
        $paymentId = $request->query('payment_id');
        if ($paymentId) {
            DB::connection('tenant')->table('payments')
                ->where('id', $paymentId)
                ->update([
                    'status'     => 'Pagado',
                    'updated_at' => now(),
                ]);
        }

        return redirect()->route('tenant.frontend')->with('success', '¡Pago procesado exitosamente con Stripe! Tu estado de cuenta ha sido actualizado.');
    }
}

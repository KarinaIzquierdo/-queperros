<?php

namespace App\Http\Controllers;

use App\Models\SponsorDog;
use App\Models\Sponsorship;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class PublicPadrinoController extends Controller
{
    /**
     * Mostrar el formulario de apadrinamiento para invitados
     */
    public function form(SponsorDog $dog)
    {
        return view('public.padrino-form', [
            'dog' => $dog
        ]);
    }

    /**
     * Procesar la solicitud de apadrinamiento y redirigir a MercadoPago
     */
    public function process(Request $request, SponsorDog $dog)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'plan' => 'required|string|in:basico,cuidador,protector',
        ]);

        $amount = match ($validated['plan']) {
            'cuidador' => 50000,
            'protector' => 100000,
            default => 30000,
        };

        // Crear registro de sponsorship (sin user_id porque es invitado)
        $sponsorship = Sponsorship::create([
            'user_id' => null,
            'sponsor_dog_id' => $dog->id,
            'plan' => $validated['plan'],
            'monto_mensual' => $amount,
            'estado' => 'Pendiente',
        ]);

        $reference = 'SPONSOR-GUEST-' . $dog->id . '-' . time();
        
        $preferenceData = [
            'items' => [
                [
                    'title' => "Apadrinamiento de {$dog->nombre} (Plan " . ucfirst($validated['plan']) . ")",
                    'quantity' => 1,
                    'currency_id' => 'COP',
                    'unit_price' => (float)$amount,
                ]
            ],
            'back_urls' => [
                'success' => route('payment.success', ['type' => 'sponsorship', 'id' => $dog->id]),
                'failure' => route('payment.failure', ['type' => 'sponsorship', 'id' => $dog->id]),
                'pending' => route('payment.pending', ['type' => 'sponsorship', 'id' => $dog->id]),
            ],
            'auto_return' => 'approved',
            'external_reference' => $reference,
            'metadata' => [
                'sponsor_dog_id' => $dog->id,
                'guest_name' => $validated['nombre'],
                'guest_email' => $validated['email'],
                'sponsorship_id' => $sponsorship->id,
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('MERCADOPAGO_ACCESS_TOKEN'),
            'Content-Type' => 'application/json',
        ])->post('https://api.mercadopago.com/checkout/preferences', $preferenceData);

        if ($response->successful()) {
            $preference = $response->json();

            // Guardar el pago pendiente
            Payment::create([
                'user_id' => null,
                'payment_type' => 'sponsorship',
                'paymentable_id' => $dog->id,
                'paymentable_type' => SponsorDog::class,
                'mercado_pago_id' => $preference['id'],
                'amount' => $amount,
                'currency' => 'COP',
                'status' => 'pending',
                'metadata' => [
                    'sponsor_dog_name' => $dog->nombre,
                    'guest_name' => $validated['nombre'],
                    'guest_email' => $validated['email'],
                    'sponsorship_id' => $sponsorship->id,
                ],
            ]);

            $initPoint = env('MERCADOPAGO_ENVIRONMENT') === 'sandbox'
                ? $preference['sandbox_init_point']
                : $preference['init_point'];

            return redirect($initPoint);
        }

        $errorData = $response->json();
        $errorMessage = $errorData['message'] ?? 'Error al crear la preferencia de pago';
        return back()->with('error', "Error de MercadoPago: $errorMessage");
    }
}

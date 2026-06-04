<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SponsorDog;
use App\Models\ServiceApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class PaymentController extends Controller
{
    /**
     * Preparar datos para el pago de apadrinamiento con MercadoPago
     */
    public function createSponsorshipPayment(Request $request, $sponsorDogId)
    {
        $sponsorDog = SponsorDog::findOrFail($sponsorDogId);
        $amount = (int)($sponsorDog->meta_mensual ?: 50000);
        $reference = 'SPONSOR-' . $sponsorDogId . '-' . time() . '-' . (Auth::id() ?: 'GUEST');
        
        $baseUrl = 'https://masqueperros.com.co';
        
        $preferenceData = [
            'items' => [
                [
                    'title' => "Apadrinamiento de {$sponsorDog->nombre}",
                    'quantity' => 1,
                    'currency_id' => 'COP',
                    'unit_price' => (float)$amount,
                ]
            ],
            'back_urls' => [
                'success' => $baseUrl . "/payment/success/sponsorship/" . $sponsorDogId,
                'failure' => $baseUrl . "/payment/failure/sponsorship/" . $sponsorDogId,
                'pending' => $baseUrl . "/payment/pending/sponsorship/" . $sponsorDogId,
            ],
            'auto_return' => 'approved',
            'external_reference' => $reference,
            'metadata' => [
                'sponsor_dog_id' => $sponsorDogId,
                'user_id' => Auth::id(),
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('MERCADOPAGO_ACCESS_TOKEN'),
            'Content-Type' => 'application/json',
        ])->post('https://api.mercadopago.com/checkout/preferences', $preferenceData);

        if ($response->successful()) {
            $preference = $response->json();

            // Guardar el pago pendiente en la base de datos
            Payment::create([
                'user_id' => Auth::id(),
                'payment_type' => 'sponsorship',
                'paymentable_id' => $sponsorDogId,
                'paymentable_type' => SponsorDog::class,
                'mercado_pago_id' => $preference['id'],
                'amount' => $amount,
                'currency' => 'COP',
                'status' => 'pending',
                'metadata' => [
                    'sponsor_dog_name' => $sponsorDog->nombre,
                    'type' => 'sponsorship'
                ],
            ]);

            $initPoint = env('MERCADOPAGO_ENVIRONMENT') === 'sandbox'
                ? $preference['sandbox_init_point']
                : $preference['init_point'];

            return redirect($initPoint);
        }

        return back()->with('error', 'Error al crear la preferencia de pago en MercadoPago.');
    }

    /**
     * Preparar datos para el pago de servicio con MercadoPago
     */
    public function createServicePayment(Request $request, $serviceApprovalId)
    {
        $serviceApproval = ServiceApproval::findOrFail($serviceApprovalId);
        
        // Asegurar que el precio sea un número válido y mayor a 0
        $amount = (int)($serviceApproval->precio ?: 0);
        if ($amount <= 0 && $serviceApproval->servicio) {
            $amount = (int)($serviceApproval->servicio->precio ?: 0);
        }

        if ($amount <= 0) {
            return back()->with('error', 'El servicio no tiene un precio válido asignado.');
        }

        $reference = 'SERVICE-' . $serviceApprovalId . '-' . time() . '-' . Auth::id();
        
        $baseUrl = 'https://masqueperros.com.co';
        
        $preferenceData = [
            'items' => [
                [
                    'title' => "Servicio: " . ($serviceApproval->servicio->nombre ?? 'Servicio'),
                    'quantity' => 1,
                    'currency_id' => 'COP',
                    'unit_price' => (float)$amount,
                ]
            ],
            'back_urls' => [
                'success' => $baseUrl . "/payment/success/service/" . $serviceApprovalId,
                'failure' => $baseUrl . "/payment/failure/service/" . $serviceApprovalId,
                'pending' => $baseUrl . "/payment/pending/service/" . $serviceApprovalId,
            ],
            'auto_return' => 'approved',
            'external_reference' => $reference,
            'metadata' => [
                'service_approval_id' => $serviceApprovalId,
                'user_id' => Auth::id(),
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('MERCADOPAGO_ACCESS_TOKEN'),
            'Content-Type' => 'application/json',
        ])->post('https://api.mercadopago.com/checkout/preferences', $preferenceData);

        if ($response->successful()) {
            $preference = $response->json();

            Payment::create([
                'user_id' => Auth::id(),
                'payment_type' => 'service',
                'paymentable_id' => $serviceApprovalId,
                'paymentable_type' => ServiceApproval::class,
                'mercado_pago_id' => $preference['id'],
                'amount' => $amount,
                'currency' => 'COP',
                'status' => 'pending',
                'metadata' => [
                    'service_name' => $serviceApproval->servicio->nombre ?? 'Servicio',
                    'pet_name' => $serviceApproval->mascota->nombre ?? 'Mascota',
                    'type' => 'service'
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

    /**
     * Preparar datos para el pago de reserva con MercadoPago
     */
    public function createReservationPayment(Request $request, $reservationId)
    {
        $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';
        $reserva = DB::table('reservas')->where($reservaKey, $reservationId)->first();
        if (!$reserva) return back()->with('error', 'Reserva no encontrada');

        $precio = (int)($reserva->precio ?: 150000);
        $reference = 'RES-' . $reservationId . '-' . time() . '-' . Auth::id();
        
        $baseUrl = 'https://masqueperros.com.co';

        $servicio = DB::table('servicios')->where('id', $reserva->id_actividad)->first();
        $mascota = DB::table('mascotas')->where('id', $reserva->id_mascota)->first();
        
        $servicioNombre = $servicio ? $servicio->nombre : 'Servicio';
        $mascotaNombre = $mascota ? $mascota->nombre : 'Mascota';

        $preferenceData = [
            'items' => [
                [
                    'title' => "Entrenamiento: {$servicioNombre} para {$mascotaNombre}",
                    'quantity' => 1,
                    'currency_id' => 'COP',
                    'unit_price' => (float)$precio,
                ]
            ],
            'back_urls' => [
                'success' => $baseUrl . "/payment/reservation/success/" . $reservationId,
                'failure' => $baseUrl . "/payment/reservation/failure/" . $reservationId,
                'pending' => $baseUrl . "/payment/reservation/pending/" . $reservationId,
            ],
            'auto_return' => 'approved',
            'external_reference' => $reference,
            'metadata' => [
                'reservation_id' => $reservationId,
                'user_id' => Auth::id(),
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('MERCADOPAGO_ACCESS_TOKEN'),
            'Content-Type' => 'application/json',
        ])->post('https://api.mercadopago.com/checkout/preferences', $preferenceData);

        if ($response->successful()) {
            $preference = $response->json();

            Payment::create([
                'user_id' => Auth::id(),
                'payment_type' => 'reservation',
                'paymentable_id' => $reservationId,
                'paymentable_type' => \App\Models\Reserva::class,
                'mercado_pago_id' => $preference['id'],
                'amount' => $precio,
                'currency' => 'COP',
                'status' => 'pending',
                'metadata' => [
                    'reservation_id' => $reservationId,
                    'type' => 'reservation'
                ],
            ]);

            $initPoint = env('MERCADOPAGO_ENVIRONMENT') === 'sandbox'
                ? $preference['sandbox_init_point']
                : $preference['init_point'];

            return redirect($initPoint);
        }

        return back()->with('error', 'Error al crear la preferencia de pago en MercadoPago.');
    }
    
    /**
     * Manejar respuesta exitosa de MercadoPago
     */
    public function success(Request $request, $type, $id)
    {
        $preferenceId = $request->get('preference_id');
        
        if ($preferenceId) {
            $payment = Payment::where('mercado_pago_id', $preferenceId)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'approved',
                    'payment_date' => now(),
                ]);
                
                if ($type === 'sponsorship') {
                    if (isset($payment->metadata['sponsorship_id'])) {
                        $sponsorship = \App\Models\Sponsorship::find($payment->metadata['sponsorship_id']);
                        if ($sponsorship) $sponsorship->update(['estado' => 'Activo']);
                    }

                    if (Auth::check()) {
                        return redirect()->route('owner.planpadrino')->with('status', '¡Pago exitoso! Gracias por apadrinar.');
                    } else {
                        $sponsorDog = SponsorDog::find($id);
                        return redirect('/')->with('status', '¡Pago exitoso! Gracias por apadrinar a ' . ($sponsorDog->nombre ?? 'este perrito') . '.');
                    }
                } elseif ($type === 'service') {
                    $serviceApproval = ServiceApproval::find($id);
                    if ($serviceApproval) $serviceApproval->update(['estado' => 'pagado']);
                    return redirect()->route('owner.services.my')->with('status', '¡Pago exitoso! Tu servicio ha sido confirmado.');
                }
            }
        }
        
        return redirect('/')->with('error', 'No se pudo verificar el pago o fue rechazado.');
    }
    
    /**
     * Webhook para recibir notificaciones de MercadoPago
     */
    public function webhook(Request $request)
    {
        $data = $request->all();
        
        if (isset($data['type']) && $data['type'] === 'payment') {
            $paymentId = $data['data']['id'];
            
            // Obtener información del pago desde MercadoPago
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('MERCADOPAGO_ACCESS_TOKEN'),
            ])->get("https://api.mercadopago.com/v1/payments/$paymentId");
            
            if ($response->successful()) {
                $paymentInfo = $response->json();
                
                $reference = $paymentInfo['external_reference'] ?? null;
                $preferenceId = $paymentInfo['preference_id'] ?? null;
                
                $payment = Payment::where('mercado_pago_id', $preferenceId)
                    ->orWhere('mercado_pago_id', $reference)
                    ->first();
                
                if ($payment) {
                    $statusMap = [
                        'approved' => 'approved',
                        'rejected' => 'rejected',
                        'cancelled' => 'cancelled',
                        'pending' => 'pending',
                    ];
                    
                    $newStatus = $statusMap[$paymentInfo['status']] ?? 'pending';
                    
                    $payment->update([
                        'status' => $newStatus,
                        'payment_date' => $paymentInfo['date_approved'] ? now() : null,
                    ]);
                    
                    // Actualizar el modelo relacionado si el pago fue aprobado
                    if ($newStatus === 'approved') {
                        if ($payment->payment_type === 'service') {
                            $serviceApproval = ServiceApproval::find($payment->paymentable_id);
                            if ($serviceApproval) {
                                $serviceApproval->update([
                                    'estado' => 'pagado',
                                ]);
                            }
                        } elseif ($payment->payment_type === 'reservation') {
                            $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';
                            DB::table('reservas')
                                ->where($reservaKey, $payment->paymentable_id)
                                ->update(['estado' => 'Pagada']);
                        } elseif ($payment->payment_type === 'sponsorship') {
                            if (isset($payment->metadata['sponsorship_id'])) {
                                $sponsorship = \App\Models\Sponsorship::find($payment->metadata['sponsorship_id']);
                                if ($sponsorship) $sponsorship->update(['estado' => 'Activo']);
                            }
                        }
                    }
                }
            }
        }
        
        return response()->json(['status' => 'ok'], 200);
    }
    
    /**
     * Manejar respuesta exitosa de pago de reserva
     */
    public function reservationSuccess(Request $request, $id)
    {
        $preferenceId = $request->get('preference_id');

        if ($preferenceId) {
            $payment = Payment::where('mercado_pago_id', $preferenceId)->first();

            if ($payment) {
                $payment->update(['status' => 'approved', 'payment_date' => now()]);
                
                $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';
                DB::table('reservas')->where($reservaKey, $id)->update(['estado' => 'Pagada']);
                
                // Notificar entrenador
                $reserva = DB::table('reservas')->where($reservaKey, $id)->first();
                if ($reserva && isset($reserva->id_empleado)) {
                    DB::table('notificaciones')->insert([
                        'user_id' => $reserva->id_empleado,
                        'tipo' => 'pago_realizado',
                        'titulo' => 'Pago Realizado',
                        'mensaje' => "El cliente ha realizado el pago del servicio.",
                        'url' => '/entrenador/reservas',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                return redirect()->route('owner.reservas')->with('status', '¡Pago exitoso! Reserva confirmada.');
            }
        }

        return redirect()->route('owner.reservas')->with('error', 'El pago no pudo ser verificado.');
    }

    /**
     * Manejar respuesta fallida de pago
     */
    public function failure(Request $request)
    {
        return redirect('/')->with('error', 'El pago fue rechazado o cancelado.');
    }

    /**
     * Manejar respuesta pendiente de pago
     */
    public function pending(Request $request)
    {
        return redirect('/')->with('status', 'El pago está pendiente de confirmación.');
    }

    public function reservationFailure(Request $request)
    {
        return redirect()->route('owner.reservas')->with('error', 'El pago de la reserva fue rechazado.');
    }

    public function reservationPending(Request $request)
    {
        return redirect()->route('owner.reservas')->with('status', 'El pago de la reserva está pendiente.');
    }
}

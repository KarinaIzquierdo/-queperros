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
     * Crear una preferencia de pago para apadrinamiento
     */
    public function createSponsorshipPayment(Request $request, $sponsorDogId)
    {
        $sponsorDog = SponsorDog::findOrFail($sponsorDogId);
        
        $preferenceData = [
            'items' => [
                [
                    'title' => "Apadrinamiento de {$sponsorDog->nombre}",
                    'quantity' => 1,
                    'currency_id' => 'COP',
                    'unit_price' => $sponsorDog->meta_mensual ?: 50000,
                ]
            ],
            'back_urls' => [
                'success' => route('payment.success', ['type' => 'sponsorship', 'id' => $sponsorDogId]),
                'failure' => route('payment.failure', ['type' => 'sponsorship', 'id' => $sponsorDogId]),
                'pending' => route('payment.pending', ['type' => 'sponsorship', 'id' => $sponsorDogId]),
            ],
            'auto_return' => 'approved',
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
            
            // Guardar el pago en la base de datos
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'payment_type' => 'sponsorship',
                'paymentable_id' => $sponsorDogId,
                'paymentable_type' => SponsorDog::class,
                'mercado_pago_id' => $preference['id'],
                'amount' => $preferenceData['items'][0]['unit_price'],
                'currency' => 'COP',
                'status' => 'pending',
                'metadata' => [
                    'sponsor_dog_name' => $sponsorDog->nombre,
                    'sponsor_dog_raza' => $sponsorDog->raza,
                ],
            ]);
            
            $initPoint = env('MERCADOPAGO_ENVIRONMENT') === 'sandbox' 
                ? $preference['sandbox_init_point'] 
                : $preference['init_point'];
            
            return redirect($initPoint);
        }
        
        return back()->with('error', 'Error al crear la preferencia de pago.');
    }
    
    /**
     * Crear una preferencia de pago para servicio
     */
    public function createServicePayment(Request $request, $serviceApprovalId)
    {
        $serviceApproval = ServiceApproval::findOrFail($serviceApprovalId);
        
        // Verificar que haya credenciales configuradas
        if (!env('MERCADOPAGO_ACCESS_TOKEN')) {
            return back()->with('error', 'Error: No se han configurado las credenciales de MercadoPago. Por favor configura MERCADOPAGO_ACCESS_TOKEN en el archivo .env');
        }
        
        $preferenceData = [
            'items' => [
                [
                    'title' => "Servicio: {$serviceApproval->servicio}",
                    'quantity' => 1,
                    'currency_id' => 'COP',
                    'unit_price' => $serviceApproval->precio ?: 0,
                ]
            ],
            'back_urls' => [
                'success' => route('payment.success', ['type' => 'service', 'id' => $serviceApprovalId]),
                'failure' => route('payment.failure', ['type' => 'service', 'id' => $serviceApprovalId]),
                'pending' => route('payment.pending', ['type' => 'service', 'id' => $serviceApprovalId]),
            ],
            'auto_return' => 'approved',
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
            
            // Guardar el pago en la base de datos
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'payment_type' => 'service',
                'paymentable_id' => $serviceApprovalId,
                'paymentable_type' => ServiceApproval::class,
                'mercado_pago_id' => $preference['id'],
                'amount' => $preferenceData['items'][0]['unit_price'],
                'currency' => 'COP',
                'status' => 'pending',
                'metadata' => [
                    'service_name' => $serviceApproval->servicio,
                    'pet_name' => $serviceApproval->mascota,
                ],
            ]);
            
            $initPoint = env('MERCADOPAGO_ENVIRONMENT') === 'sandbox' 
                ? $preference['sandbox_init_point'] 
                : $preference['init_point'];
            
            return redirect($initPoint);
        }
        
        // Mostrar error detallado de la API de MercadoPago
        $errorData = $response->json();
        $errorMessage = $errorData['message'] ?? 'Error al crear la preferencia de pago';
        
        return back()->with('error', "Error de MercadoPago: $errorMessage");
    }
    
    /**
     * Crear una preferencia de pago para reserva de entrenamiento
     */
    public function createReservationPayment(Request $request, $reservationId)
    {
        // Verificar que haya credenciales configuradas
        if (!env('MERCADOPAGO_ACCESS_TOKEN')) {
            return back()->with('error', 'Error: No se han configurado las credenciales de MercadoPago. Por favor configura MERCADOPAGO_ACCESS_TOKEN en el archivo .env');
        }
        
        // Obtener la reserva
        $reserva = DB::table('reservas')->where('id', $reservationId)->first();
        
        if (!$reserva) {
            return back()->with('error', 'Reserva no encontrada');
        }
        
        // Obtener información del servicio y mascota
        $servicio = DB::table('servicios')->where('id', $reserva->id_actividad)->first();
        $mascota = DB::table('mascotas')->where('id', $reserva->id_mascota)->first();
        
        // Precio por defecto para entrenamiento
        $precio = 150000; // Precio base para entrenamiento
        
        $servicioNombre = $servicio ? $servicio->nombre : 'Servicio';
        $mascotaNombre = $mascota ? $mascota->nombre : 'Mascota';
        
        $preferenceData = [
            'items' => [
                [
                    'title' => "Entrenamiento: {$servicioNombre} para {$mascotaNombre}",
                    'quantity' => 1,
                    'currency_id' => 'COP',
                    'unit_price' => $precio,
                ]
            ],
            'back_urls' => [
                'success' => route('payment.reservation.success', ['id' => $reservationId]),
                'failure' => route('payment.reservation.failure', ['id' => $reservationId]),
                'pending' => route('payment.reservation.pending', ['id' => $reservationId]),
            ],
            'auto_return' => 'approved',
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
            
            // Guardar el pago en la base de datos (usando paymentable_type null para reservas directas)
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'payment_type' => 'reservation',
                'paymentable_id' => $reservationId,
                'paymentable_type' => null, // Reservas directas no tienen modelo polimórfico
                'mercado_pago_id' => $preference['id'],
                'amount' => $precio,
                'currency' => 'COP',
                'status' => 'pending',
                'metadata' => [
                    'service_name' => $servicio->nombre ?? 'Servicio',
                    'pet_name' => $mascota->nombre ?? 'Mascota',
                    'reservation_id' => $reservationId,
                ],
            ]);
            
            $initPoint = env('MERCADOPAGO_ENVIRONMENT') === 'sandbox' 
                ? $preference['sandbox_init_point'] 
                : $preference['init_point'];
            
            return redirect($initPoint);
        }
        
        // Mostrar error detallado de la API de MercadoPago
        $errorData = $response->json();
        $errorMessage = $errorData['message'] ?? 'Error al crear la preferencia de pago';
        
        return back()->with('error', "Error de MercadoPago: $errorMessage");
    }
    
    /**
     * Manejar respuesta exitosa de MercadoPago
     */
    public function success(Request $request, $type, $id)
    {
        $paymentId = $request->get('preference_id') ?? $request->get('payment_id');
        
        if ($paymentId) {
            $payment = Payment::where('mercado_pago_id', $paymentId)->first();
            
            if ($payment) {
                // Actualizar estado del pago
                $payment->update([
                    'status' => 'approved',
                    'payment_date' => now(),
                ]);
                
                // Actualizar el modelo relacionado según el tipo
                if ($type === 'sponsorship') {
                    $sponsorDog = SponsorDog::find($id);
                    if ($sponsorDog) {
                        // Aquí puedes agregar lógica adicional para el apadrinamiento
                        // Por ejemplo, incrementar contador de padrinos
                    }
                    
                    return redirect()->route('owner.planpadrino')
                        ->with('status', '¡Pago exitoso! Gracias por apadrinar a este perrito.');
                } elseif ($type === 'service') {
                    $serviceApproval = ServiceApproval::find($id);
                    if ($serviceApproval) {
                        $serviceApproval->update([
                            'estado' => 'pagado',
                        ]);
                        
                        // Crear la reserva automáticamente
                        // Aquí iría la lógica para crear la reserva
                    }
                    
                    return redirect()->route('owner.services.my')
                        ->with('status', '¡Pago exitoso! Tu servicio ha sido confirmado.');
                }
            }
        }
        
        return redirect()->back()->with('error', 'No se pudo procesar el pago.');
    }
    
    /**
     * Manejar respuesta fallida de MercadoPago
     */
    public function failure(Request $request, $type, $id)
    {
        $paymentId = $request->get('preference_id') ?? $request->get('payment_id');
        
        if ($paymentId) {
            $payment = Payment::where('mercado_pago_id', $paymentId)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'rejected',
                ]);
            }
        }
        
        return redirect()->back()->with('error', 'El pago fue rechazado. Por favor intenta nuevamente.');
    }
    
    /**
     * Manejar respuesta pendiente de MercadoPago
     */
    public function pending(Request $request, $type, $id)
    {
        $paymentId = $request->get('preference_id') ?? $request->get('payment_id');
        
        if ($paymentId) {
            $payment = Payment::where('mercado_pago_id', $paymentId)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'pending',
                ]);
            }
        }
        
        return redirect()->back()->with('status', 'El pago está pendiente de procesamiento.');
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
                
                // Buscar el pago local
                $payment = Payment::where('mercado_pago_id', $paymentId)->first();
                
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
                        'payment_method' => $paymentInfo['payment_method_id'] ?? null,
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
                            // Actualizar estado de la reserva
                            DB::table('reservas')
                                ->where('id', $payment->paymentable_id)
                                ->update(['estado' => 'Pagada']);
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
        $paymentId = $request->get('preference_id') ?? $request->get('payment_id');

        if ($paymentId) {
            $payment = Payment::where('mercado_pago_id', $paymentId)->first();

            if ($payment) {
                $payment->update([
                    'status' => 'approved',
                    'payment_date' => now(),
                ]);

                // Obtener la reserva para enviar notificación al entrenador
                $reserva = DB::table('reservas')->where('id', $id)->first();

                // Actualizar estado de la reserva
                DB::table('reservas')
                    ->where('id', $id)
                    ->update(['estado' => 'Pagada']);

                // Enviar notificación al entrenador
                if ($reserva && Schema::hasTable('notificaciones') && isset($reserva->id_empleado)) {
                    try {
                        DB::table('notificaciones')->insert([
                            'user_id' => $reserva->id_empleado,
                            'tipo' => 'pago_realizado',
                            'titulo' => 'Pago Realizado',
                            'mensaje' => "El cliente ha realizado el pago del servicio. La reserva está confirmada.",
                            'url' => '/entrenador/reservas',
                            'leida_en' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Error creating notification: ' . $e->getMessage());
                    }
                }
            }
        }

        return redirect()->route('owner.reservas')
            ->with('status', '¡Pago exitoso! Tu reserva de entrenamiento ha sido confirmada.');
    }
    
    /**
     * Manejar respuesta fallida de pago de reserva
     */
    public function reservationFailure(Request $request, $id)
    {
        $paymentId = $request->get('preference_id') ?? $request->get('payment_id');
        
        if ($paymentId) {
            $payment = Payment::where('mercado_pago_id', $paymentId)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'rejected',
                ]);
            }
        }
        
        return redirect()->route('owner.reservas')
            ->with('error', 'El pago fue rechazado. Por favor intenta nuevamente.');
    }
    
    /**
     * Manejar respuesta pendiente de pago de reserva
     */
    public function reservationPending(Request $request, $id)
    {
        $paymentId = $request->get('preference_id') ?? $request->get('payment_id');
        
        if ($paymentId) {
            $payment = Payment::where('mercado_pago_id', $paymentId)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'pending',
                ]);
            }
        }
        
        return redirect()->route('owner.reservas')
            ->with('status', 'El pago está pendiente de procesamiento.');
    }
}

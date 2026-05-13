<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class CaregiverDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'appointments_today' => 4,
            'total_pets' => 3,
            'completed_consults' => 3,
        ];

        $appointments = [
            [
                'time' => '16:20:00',
                'pet' => 'LULU',
                'owner' => 'Propietario',
            ],
            [
                'time' => '16:20:00',
                'pet' => 'LULU',
                'owner' => 'Propietario',
            ],
            [
                'time' => '16:20:00',
                'pet' => 'LULU',
                'owner' => 'Propietario',
            ],
            [
                'time' => '16:20:00',
                'pet' => 'LULU',
                'owner' => 'Propietario',
            ],
        ];

        $recentPets = [
            [
                'name' => 'LULU',
                'breed' => 'Pincher',
                'age' => '5.43 años',
            ],
            [
                'name' => 'LULU',
                'breed' => 'Pincher',
                'age' => '5.43 años',
            ],
            [
                'name' => 'LULU',
                'breed' => 'Pincher',
                'age' => '5.43 años',
            ],
        ];

        return view('entrenador.dashboardentrenador', [
            'user' => $user,
            'kpis' => [
                'pending_reservations' => $stats['appointments_today'] ?? 0,
                'confirmed_reservations' => $stats['completed_consults'] ?? 0,
                'weekly_appointments' => $stats['appointments_today'] ?? 0,
                'monthly_income' => 0,
            ],
            'pendingReservations' => array_map(function($app) {
                return [
                    'pet' => $app['pet'],
                    'owner' => $app['owner'],
                    'status' => 'PENDIENTE',
                    'service' => 'Entrenamiento',
                    'date' => $app['time'],
                    'price' => 50000,
                ];
            }, $appointments),
        ]);
    }
}

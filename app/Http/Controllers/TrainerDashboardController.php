<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class TrainerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $kpis = [
            'pending_reservations' => 3,
            'confirmed_reservations' => 3,
            'weekly_appointments' => 6,
            'monthly_income' => 110000,
        ];

        $pendingReservations = [
            [
                'pet' => 'Max',
                'owner' => 'Carlos Rodriguez',
                'service' => 'Paseo matutino',
                'date' => '2026-03-24',
                'price' => 15000,
                'status' => 'PENDIENTE',
            ],
            [
                'pet' => 'Luna',
                'owner' => 'Maria Garcia',
                'service' => 'Entrenamiento avanzado',
                'date' => '2026-03-24',
                'price' => 50000,
                'status' => 'PENDIENTE',
            ],
            [
                'pet' => 'Rocky',
                'owner' => 'Ana Martinez',
                'service' => 'Cuidado diario',
                'date' => '2026-03-25',
                'price' => 45000,
                'status' => 'PENDIENTE',
            ],
        ];

        return view('entrenador.dashboardentrenador', [
            'user' => $user,
            'kpis' => $kpis,
            'pendingReservations' => $pendingReservations,
        ]);
    }
}

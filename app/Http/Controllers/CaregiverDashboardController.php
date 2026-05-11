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

        return view('cuidador.dashboardcuidador', [
            'user' => $user,
            'stats' => $stats,
            'appointments' => $appointments,
            'recentPets' => $recentPets,
        ]);
    }
}

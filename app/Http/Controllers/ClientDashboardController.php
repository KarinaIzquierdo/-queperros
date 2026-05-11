<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('dueños.dashboarddueño', [
            'user' => $user,
        ]);
    }
}

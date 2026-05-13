<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pets = collect();

        if (Schema::hasTable('mascotas') && Schema::hasColumn('mascotas', 'id_dueno')) {
            $pets = DB::table('mascotas')
                ->where('id_dueno', (int) $user->id)
                ->orderBy('nombre')
                ->get();
        }

        return view('dueños.dashboarddueño', [
            'user' => $user,
            'featuredPet' => $pets->first(),
            'petCount' => $pets->count(),
        ]);
    }
}

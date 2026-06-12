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

        // Conteo de reservas activas
        $activeReservationsCount = 0;
        if (Schema::hasTable('reservas')) {
            $mascotaKey = Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';
            $activeReservationsCount = DB::table('reservas as r')
                ->join('mascotas as m', "m.$mascotaKey", '=', 'r.id_mascota')
                ->where('m.id_dueno', (int) $user->id)
                ->whereNotIn('r.estado', ['Finalizada', 'Cancelada', 'Rechazada'])
                ->count();
        }

        // Conteo de notificaciones sin leer
        $unreadCount = 0;
        if (Schema::hasTable('notifications')) {
            $unreadCount += DB::table('notifications')
                ->where('id_usuario', (int) $user->id)
                ->where('leido', false)
                ->count();
        }
        if (Schema::hasTable('notificaciones')) {
            $unreadCount += DB::table('notificaciones')
                ->where('user_id', (int) $user->id)
                ->whereNull('leida_en')
                ->count();
        }

        // Conteo de reportes de seguimiento
        $reportsCount = 0;
        if (Schema::hasTable('seguimientos')) {
            $mascotaKey = Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';
            $reportsCount = DB::table('seguimientos as s')
                ->join('mascotas as m', "m.$mascotaKey", '=', 's.id_mascota')
                ->where('m.id_dueno', (int) $user->id)
                ->count();
        }

        return view('dueños.dashboarddueño', [
            'user' => $user,
            'featuredPet' => $pets->first(),
            'petCount' => $pets->count(),
            'counts' => [
                'activas' => $activeReservationsCount,
                'reports' => $reportsCount,
            ],
            'unreadCount' => $unreadCount,
        ]);
    }
}

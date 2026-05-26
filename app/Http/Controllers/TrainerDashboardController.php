<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TrainerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pendingReservations = collect();
        $pendingCount = 0;
        $confirmedCount = 0;
        $weeklyCount = 0;
        $monthlyIncome = 0;

        if (Schema::hasTable('reservas')) {
            $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';
            $hasMascotas = Schema::hasTable('mascotas');
            $mascotaKey = $hasMascotas && Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';
            $hasServicios = Schema::hasTable('servicios');
            $hasActividades = !$hasServicios && Schema::hasTable('actividades');
            $hasUsers = Schema::hasTable('users');

            $base = DB::table('reservas as r')->where('r.id_empleado', (int) $user->id);
            if ($hasServicios) {
                $base->leftJoin('servicios as sf', 'sf.id', '=', 'r.id_actividad');
            } elseif ($hasActividades) {
                $base->leftJoin('actividades as af', 'af.id_actividad', '=', 'r.id_actividad');
            }

            $pendingCount = (clone $base)->where('r.estado', 'Pendiente')->count();
            $confirmedCount = (clone $base)->where('r.estado', 'Confirmada')->count();
            $weeklyCount = (clone $base)
                ->whereBetween('r.fecha', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])
                ->count();

            if ($hasServicios) {
                $monthlyIncome = (clone $base)
                    ->join('servicios as s', 's.id', '=', 'r.id_actividad')
                    ->where('r.estado', 'Finalizada')
                    ->whereMonth('r.fecha', now()->month)
                    ->whereYear('r.fecha', now()->year)
                    ->sum('s.precio');
            }

            $query = DB::table('reservas as r')->where('r.id_empleado', (int) $user->id);

            if ($hasMascotas) {
                $query->leftJoin('mascotas as m', "m.$mascotaKey", '=', 'r.id_mascota');
            }
            if ($hasServicios) {
                $query->leftJoin('servicios as s', 's.id', '=', 'r.id_actividad');
            } elseif ($hasActividades) {
                $query->leftJoin('actividades as a', 'a.id_actividad', '=', 'r.id_actividad');
            }
            if ($hasMascotas && $hasUsers && Schema::hasColumn('mascotas', 'id_dueno')) {
                $query->leftJoin('users as u', 'u.id', '=', 'm.id_dueno');
            }

            $pendingReservations = $query
                ->where('r.estado', 'Pendiente')
                ->orderByDesc("r.$reservaKey")
                ->limit(3)
                ->select([
                    DB::raw("r.$reservaKey as id"),
                    DB::raw($hasMascotas ? 'COALESCE(m.nombre, "") as pet' : '"" as pet'),
                    DB::raw($hasUsers ? 'COALESCE(u.name, "") as owner' : '"" as owner'),
                    DB::raw($hasServicios ? 'COALESCE(s.nombre, "") as service' : ($hasActividades ? 'COALESCE(a.tipo_actividad, "") as service' : '"" as service')),
                    DB::raw('COALESCE(r.fecha, "") as date'),
                    DB::raw($hasServicios ? 'COALESCE(s.precio, 0) as price' : '0 as price'),
                    DB::raw('COALESCE(r.estado, "Pendiente") as status'),
                ])
                ->get()
                ->map(fn ($r) => [
                    'id' => $r->id,
                    'pet' => $r->pet,
                    'owner' => $r->owner,
                    'service' => $r->service,
                    'date' => $r->date,
                    'price' => $r->price,
                    'status' => mb_strtoupper((string) $r->status),
                ]);
        }

        $kpis = [
            'pending_reservations' => $pendingCount,
            'confirmed_reservations' => $confirmedCount,
            'weekly_appointments' => $weeklyCount,
            'monthly_income' => $monthlyIncome,
        ];

        return view('entrenador.dashboardentrenador', [
            'user' => $user,
            'kpis' => $kpis,
            'pendingReservations' => $pendingReservations,
        ]);
    }
}

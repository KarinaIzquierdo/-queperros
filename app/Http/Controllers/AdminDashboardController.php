<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $users = User::query()->orderByDesc('id')->get();
        $recentUsers = User::query()->orderByDesc('id')->limit(4)->get();
        $recentReservations = collect();
        $todayAppointments = 0;
        $activeServices = 0;

        $ownersCount = User::query()->where('rol', 'dueno')->count();
        $vetsCount = User::query()->where('rol', 'empleado')->count();
        $adminsCount = User::query()->where('rol', 'admin')->count();
        $definedRoles = $users->pluck('rol')->filter()->unique()->sort()->values();
        $definedRolesCount = $definedRoles->count();

        if (Schema::hasTable('servicios')) {
            $activeServices = Schema::hasColumn('servicios', 'activo')
                ? DB::table('servicios')->where('activo', true)->count()
                : DB::table('servicios')->count();
        }

        if (Schema::hasTable('reservas')) {
            $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';
            $hasMascotas = Schema::hasTable('mascotas');
            $mascotaKey = $hasMascotas && Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';
            $hasServicios = Schema::hasTable('servicios');
            $hasActividades = !$hasServicios && Schema::hasTable('actividades');
            $hasUsers = Schema::hasTable('users');

            $todayAppointments = Schema::hasColumn('reservas', 'fecha')
                ? DB::table('reservas')->whereDate('fecha', now()->toDateString())->count()
                : DB::table('reservas')->count();

            $query = DB::table('reservas as r');

            if ($hasMascotas) {
                $query->leftJoin('mascotas as m', "m.$mascotaKey", '=', 'r.id_mascota');
            }
            if ($hasServicios) {
                $query->leftJoin('servicios as s', 's.id', '=', 'r.id_actividad');
            } elseif ($hasActividades) {
                $query->leftJoin('actividades as a', 'a.id_actividad', '=', 'r.id_actividad');
            }
            if ($hasUsers && Schema::hasColumn('reservas', 'id_empleado')) {
                $query->leftJoin('users as e', 'e.id', '=', 'r.id_empleado');
            }

            $recentReservations = $query
                ->orderByDesc("r.$reservaKey")
                ->limit(5)
                ->select([
                    DB::raw("r.$reservaKey as id"),
                    DB::raw('COALESCE(r.estado, "") as status'),
                    DB::raw('COALESCE(r.fecha, "") as date'),
                    DB::raw($hasMascotas ? 'COALESCE(m.nombre, "Mascota") as pet' : '"Mascota" as pet'),
                    DB::raw($hasServicios ? 'COALESCE(s.nombre, "Servicio") as service' : ($hasActividades ? 'COALESCE(a.tipo_actividad, "Servicio") as service' : '"Servicio" as service')),
                    DB::raw($hasUsers ? 'COALESCE(e.name, "Sin asignar") as trainer' : '"Sin asignar" as trainer'),
                    DB::raw('COALESCE(r.created_at, r.fecha) as created_at'),
                ])
                ->get();
        }

        $stats = [
            'total_users' => User::query()->count(),
            'active_services' => $activeServices,
            'defined_roles' => $definedRolesCount,
            'roles_list' => $definedRoles,
            'owners_count' => $ownersCount,
            'vets_count' => $vetsCount,
            'admins_count' => $adminsCount,
            'today_appointments' => $todayAppointments,
        ];

        return view('admin.dashboardAdmin', [
            'user' => $user,
            'stats' => $stats,
            'users' => $users,
            'recentUsers' => $recentUsers,
            'rolesList' => $definedRoles,
            'recentReservations' => $recentReservations,
        ]);
    }
}

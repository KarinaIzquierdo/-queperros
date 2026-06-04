<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
        $trainersCount = User::query()->where('rol', 'entrenador')->count();
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
            $hasCreatedAt = Schema::hasColumn('reservas', 'created_at');
            $hasFecha = Schema::hasColumn('reservas', 'fecha');

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
                    DB::raw($hasFecha ? 'COALESCE(r.fecha, "") as date' : '"" as date'),
                    DB::raw($hasMascotas ? 'COALESCE(m.nombre, "Mascota") as pet' : '"Mascota" as pet'),
                    DB::raw($hasServicios ? 'COALESCE(s.nombre, "Servicio") as service' : ($hasActividades ? 'COALESCE(a.tipo_actividad, "Servicio") as service' : '"Servicio" as service')),
                    DB::raw($hasUsers ? 'COALESCE(e.name, "Sin asignar") as trainer' : '"Sin asignar" as trainer'),
                    DB::raw($hasCreatedAt ? 'COALESCE(r.created_at, r.fecha) as created_at' : ($hasFecha ? 'r.fecha as created_at' : '"" as created_at')),
                ])
                ->get();
        }

        $stats = [
            'total_users' => User::query()->count(),
            'active_services' => $activeServices,
            'defined_roles' => $definedRolesCount,
            'roles_list' => $definedRoles,
            'owners_count' => $ownersCount,
            'trainers_count' => $trainersCount,
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

    public function notificaciones()
    {
        $user = Auth::user();

        // Marcar todas como leídas inmediatamente al abrir la sección (Opción A) en ambas tablas
        if (Schema::hasTable('notificaciones')) {
            DB::table('notificaciones')
                ->where('user_id', (int) $user->id)
                ->whereNull('leida_en')
                ->update([
                    'leida_en' => now(),
                ]);
        }
        if (Schema::hasTable('notifications')) {
            DB::table('notifications')
                ->where('id_usuario', (int) $user->id)
                ->where('leido', false)
                ->update([
                    'leido' => true,
                    'leido_en' => now(),
                ]);
        }

        $notifications = collect();

        if (Schema::hasTable('notificaciones')) {
            $spanish = DB::table('notificaciones')
                ->where('user_id', (int) $user->id)
                ->get()
                ->map(function ($n) {
                    return (object) [
                        'id' => $n->id,
                        'source' => 'notificaciones',
                        'tipo' => $n->tipo,
                        'titulo' => $n->titulo ?? ucfirst(str_replace('_', ' ', $n->tipo ?? 'notificacion')),
                        'mensaje' => $n->mensaje,
                        'url' => $n->url,
                        'leido' => !empty($n->leida_en),
                        'leida_en' => $n->leida_en,
                        'created_at' => $n->created_at,
                    ];
                });
            $notifications = $notifications->concat($spanish);
        }

        if (Schema::hasTable('notifications')) {
            $english = DB::table('notifications')
                ->where('id_usuario', (int) $user->id)
                ->get()
                ->map(function ($n) {
                    return (object) [
                        'id' => $n->id,
                        'source' => 'notifications',
                        'tipo' => $n->tipo,
                        'titulo' => $n->titulo ?? ucfirst(str_replace('_', ' ', $n->tipo ?? 'notificacion')),
                        'mensaje' => $n->mensaje,
                        'url' => $n->url,
                        'leido' => (bool) ($n->leido || !empty($n->leido_en)),
                        'leida_en' => $n->leido_en ?? ($n->leido ? now() : null),
                        'created_at' => $n->created_at,
                    ];
                });
            $notifications = $notifications->concat($english);
        }

        $notifications = $notifications->sortByDesc('created_at')->values();

        return view('admin.notificaciones', [
            'user' => $user,
            'notifications' => $notifications,
            'unreadCount' => 0, // Todas acaban de ser marcadas como leídas
        ]);
    }

    public function markNotificationAsRead($id)
    {
        $userId = Auth::id();

        if (Schema::hasTable('notificaciones')) {
            DB::table('notificaciones')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->update([
                    'leida_en' => now(),
                ]);
        }

        if (Schema::hasTable('notifications')) {
            DB::table('notifications')
                ->where('id', $id)
                ->where('id_usuario', $userId)
                ->update([
                    'leido' => true,
                    'leido_en' => now(),
                ]);
        }

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead()
    {
        $userId = Auth::id();

        if (Schema::hasTable('notificaciones')) {
            DB::table('notificaciones')
                ->where('user_id', $userId)
                ->whereNull('leida_en')
                ->update([
                    'leida_en' => now(),
                ]);
        }

        if (Schema::hasTable('notifications')) {
            DB::table('notifications')
                ->where('id_usuario', $userId)
                ->where('leido', false)
                ->update([
                    'leido' => true,
                    'leido_en' => now(),
                ]);
        }

        return response()->json(['success' => true]);
    }

    public function approvals()
    {
        $admin = Auth::user();

        $approvals = collect();
        if (Schema::hasTable('service_approvals')) {
            $approvals = DB::table('service_approvals as sa')
                ->leftJoin('users as u', 'sa.id_usuario', '=', 'u.id')
                ->leftJoin('mascotas as m', 'sa.id_mascota', '=', 'm.id_mascota')
                ->leftJoin('servicios as s', 'sa.id_servicio', '=', 's.id')
                ->select([
                    'sa.*',
                    'u.name as usuario_nombre',
                    'm.nombre as mascota_nombre',
                    's.nombre as servicio_nombre',
                    's.precio as servicio_precio',
                ])
                ->orderByDesc('sa.created_at')
                ->get();
        }

        $stats = [
            'pending' => $approvals->where('estado', 'pendiente')->count(),
            'approved' => $approvals->where('estado', 'aprobado')->count(),
            'rejected' => $approvals->where('estado', 'rechazado')->count(),
            'paid' => $approvals->where('estado', 'pagado')->count(),
        ];

        return view('admin.approvals', [
            'admin' => $admin,
            'approvals' => $approvals,
            'stats' => $stats,
        ]);
    }

    public function approveService(Request $request, $id)
    {
        if (Schema::hasTable('service_approvals')) {
            // Obtener la solicitud para enviar notificación
            $approval = DB::table('service_approvals')->where('id', $id)->first();
            
            DB::table('service_approvals')
                ->where('id', $id)
                ->update([
                    'estado' => 'aprobado',
                    'notas_admin' => $request->notas_admin,
                    'fecha_aprobacion' => now(),
                ]);

            // Enviar notificación al dueño
            if ($approval && Schema::hasTable('notifications')) {
                $service = DB::table('servicios')->where('id', $approval->id_servicio)->first();
                $mascota = DB::table('mascotas')->where('id_mascota', $approval->id_mascota)->first();
                
                DB::table('notifications')->insert([
                    'id_usuario' => $approval->id_usuario,
                    'tipo' => 'servicio_aprobado',
                    'mensaje' => "¡Tu solicitud de '{$service->nombre}' para {$mascota->nombre} ha sido aprobada! Ahora puedes proceder con el pago.",
                    'url' => '/dashboard/mis-servicios',
                    'leido' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.approvals.index')
            ->with('success', 'Servicio aprobado correctamente.');
    }

    public function rejectService(Request $request, $id)
    {
        if (Schema::hasTable('service_approvals')) {
            // Obtener la solicitud para enviar notificación
            $approval = DB::table('service_approvals')->where('id', $id)->first();
            
            DB::table('service_approvals')
                ->where('id', $id)
                ->update([
                    'estado' => 'rechazado',
                    'notas_admin' => $request->notas_admin,
                ]);

            // Enviar notificación al dueño
            if ($approval && Schema::hasTable('notifications')) {
                $service = DB::table('servicios')->where('id', $approval->id_servicio)->first();
                $mascota = DB::table('mascotas')->where('id_mascota', $approval->id_mascota)->first();
                
                DB::table('notifications')->insert([
                    'id_usuario' => $approval->id_usuario,
                    'tipo' => 'servicio_rechazado',
                    'mensaje' => "Tu solicitud de '{$service->nombre}' para {$mascota->nombre} ha sido rechazada. " . ($request->notas_admin ? "Motivo y días sugeridos: {$request->notas_admin}" : "Por favor, contacta al administrador para conocer la disponibilidad."),
                    'url' => '/dashboard/mis-servicios',
                    'leido' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.approvals.index')
            ->with('success', 'Servicio rechazado correctamente.');
    }

    public function confirmPayment($id)
    {
        if (Schema::hasTable('service_approvals')) {
            $approval = DB::table('service_approvals')->where('id', $id)->first();
            
            if ($approval && $approval->estado === 'aprobado') {
                DB::table('service_approvals')
                    ->where('id', $id)
                    ->update([
                        'estado' => 'pagado',
                        'fecha_pago' => now(),
                    ]);

                // Crear la reserva real
                if (Schema::hasTable('reservas')) {
                    DB::table('reservas')->insert([
                        'id_mascota' => $approval->id_mascota,
                        'id_empleado' => null,
                        'id_actividad' => $approval->id_servicio,
                        'fecha' => $approval->fecha_solicitada,
                        'estado' => 'Confirmada',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->route('admin.approvals.index')
            ->with('success', 'Pago confirmado correctamente.');
    }
}
